<?php

/**
 * Opcacheを大活用したキャッシュ。共有メモリに乗るので同一PHP実行環境でシェアされる。
 * キャッシュにのれば配列のデシリアライズという概念がないので、高速（の筈）
 * opcache.memory_consumption を高めに。
 * できればpreloadすること。
 * find store |grep php |xargs rm ; php generate.php
 * Author: uzulla <zishida@gmail.com>
 */

// 最新のファイル以外を削除するシェルスクリプト例
// find store -type f -name "*.php" -not -path `find store -type f -name "*.php" |sort |tail -1`|sort |xargs rm
// これをしないと、Preloadが重くなる(かも)、万以上なら考えること
// prefixをいくつか種類作るなら、もうちょっと工夫が必要

define("CACHE_STORED_DIR", __DIR__ . "/store/");

/**
 * キャッシュされたデータを取得する, prefixは実質名前
 * ※ 数万オーダーになると遅くなりはじめる
 * @param string $prefix
 * @return mixed
 */
function opc_cache_get(string $prefix = "cached_")
{
    $list = array_values(get_defined_functions()['user']);
    $filtered = array_filter($list, function ($func_name) use ($prefix) {
        return (strpos($func_name, $prefix) !== false);
    });

    if (count($filtered) === 0) {
        throw new RuntimeException("cache not found. prefix:{$prefix}");
    }

    sort($filtered);
    $func_name = array_pop($filtered);

    return call_user_func($func_name);
}

/**
 * データをコードに変換してストアする。また、 opcacheにプリコンパイルして乗せる
 * ※ 数千、数万更新するならopcache.memory_consumptionを十分に大きくする
 * ※ シリアライズはしてはならない、生のPHP配列（等）をいれること。ただしvar_exportできるものであること
 * @param string $prefix
 * @param array $data
 */
function opc_cache_store(string $prefix = "cached_", $data = [])
{
    // マイクロ秒でファイル名を生成する
    $suffix = (string)microtime(true) * 10000;
    // dataをPHPコードに変換
    $code = '<?php ' . PHP_EOL . "function {$prefix}{$suffix}(){ return " . var_export($data, true) . " ; }" . PHP_EOL;
    file_put_contents(CACHE_STORED_DIR . "{$prefix}{$suffix}.php_", $code);
    // 念の為、Atomicにしないと壊れるかもしれないので
    rename(CACHE_STORED_DIR . "{$prefix}{$suffix}.php_", CACHE_STORED_DIR . "{$prefix}{$suffix}.php");
    // コンパイルしてopcacheに乗せる
    opcache_compile_file(CACHE_STORED_DIR . "{$prefix}{$suffix}.php");
}

/**
 * preloadのためのメソッド、最新版だけを取得する
 * @param string $prefix
 */
function opc_preload(string $prefix = "cached_"): void
{
    // globを使うので、ext2とかだとファイルが増えるとpreload時にスゴイ重くなるかもしれない
    // 定期的に消したほうが良いかも
    $list = glob(CACHE_STORED_DIR . "{$prefix}*.php");
    // globはソート済み結果で返ってくるので、最後を使う
    $file = array_pop($list);
    opcache_compile_file($file);
}
