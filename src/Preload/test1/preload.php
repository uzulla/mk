<?php

$list = glob(__DIR__.'/store/*.php');
foreach ($list as $file) {
    opcache_compile_file($file);
}
