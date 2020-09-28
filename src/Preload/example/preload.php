<?php

function opc_cached_preloaded(){} // preloaded mark
ini_set('memory_limit',  1024*1024*128);
require_once ("opcache_cache.php");
opc_preload("some_");
