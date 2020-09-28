<?php

// データ大量生成サンプル（ベンチ等用）

require_once ("opcache_cache.php");

// generate tons of files.
for ($i = 0; $i < 10000; $i++) {
    opc_cache_store("some_", ini_get_all());
}
