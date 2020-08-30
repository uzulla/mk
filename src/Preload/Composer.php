<?php
// php.ini
// opcache.preload=/abs/path/this.php
// and reload php

// composer dump-autoload --optimize

$files = require __DIR__ . '/vendor/composer/autoload_classmap.php';
$unique_files = array_unique($files);

// add hand made. lol
$unique_files[] = __DIR__ ."/index.php";
$unique_files[] = __DIR__ ."/lib/Torb/HumanReadableFormatter.php";
$unique_files[] = __DIR__ ."/lib/Torb/PDOStatementUzuWrapper.php";
$unique_files[] = __DIR__ ."/lib/Torb/PDOWrapper.php";
$unique_files[] = __DIR__ ."/lib/Torb/PDOUzuWrapper.php";
$unique_files[] = __DIR__ ."/run_local.php";
$unique_files[] = __DIR__ ."/app.php";

foreach ($unique_files as $file) {
    @opcache_compile_file($file);
    // echo $file.PHP_EOL;
}
