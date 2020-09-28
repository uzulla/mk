<?php

// preloadしたかどうかのフラグ代わり
// 明示でPreloadするなら、いらない。
function my_preloaded()
{
    return true;
}

// globはファイル名でソートされた結果がかえってくる
// https://www.php.net/manual/ja/function.glob.php
// GLOB_NOSORT - (略)。このフラグを使わない場合は、パス名をアルファベット順にソートします。
$list = glob(__DIR__ . '/store/*.php');

// 以下はopcache.memory_consumptionパラメタの実験的なベンチマーク目的なので、実際にはよろしくないね
//ini_set("memory_limit", 4000 * 1024 * 1024);
//foreach ($list as $file) {
//    opcache_compile_file($file);
//}

// 最新一件だけでいいんだから、以下であるべきだろう。
$latest_file = array_pop($list);
opcache_compile_file($latest_file);
