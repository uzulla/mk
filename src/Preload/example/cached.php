<?php

// php -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.interned_strings_buffer=128 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080

require_once ("opcache_cache.php");

function heavy_calc_sample(){
    usleep(10*1000);
    return ini_get_all();
}

// preloadを使えない場合だけいれる
// opc_preload("some_");

$val = null;
try {
    $val = opc_cache_get("some_");
}catch(Throwable $e){
    echo "キャッシュが空など {$e->getMessage()}";
    opc_cache_store("some_", heavy_calc_sample());
    $val = opc_cache_get("some_");
}

//var_dump($val);

if(rand(1,10) === 2){
    // ランダムにキャッシュを増加させる
    opc_cache_store("some_", heavy_calc_sample());
}

// 単なるテスト出力
var_export($val);