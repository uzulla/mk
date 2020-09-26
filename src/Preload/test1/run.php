<?php

// rm store/*
// php generate.php
// php generate.php
// php generate.php
// php generate.php

// php -dopcache.preload=preload.php -dopcache.memory_consumption=512 -S 127.0.0.1:8080
// php -S 127.0.0.1:8080

$list = glob(__DIR__.'/store/*.php');

$time = microtime(true);

foreach($list as $file){
    require($file);
}

echo $GLOBALS['time'] . PHP_EOL;

echo (microtime(true) - $time) ." sec".PHP_EOL;

/*
# disable preload
Concurrency Level:      1
Time taken for tests:   8.323 seconds
Complete requests:      10
Failed requests:        9
   (Connect: 0, Receive: 0, Length: 9, Exceptions: 0)
Total transferred:      1941 bytes
HTML transferred:       321 bytes
Requests per second:    1.20 [#/sec] (mean)
Time per request:       832.344 [ms] (mean)
Time per request:       832.344 [ms] (mean, across all concurrent requests)

# enable preload
$ ab -n 10 http://127.0.0.1:8080/run.php

Concurrency Level:      1
Time taken for tests:   0.015 seconds
Complete requests:      10
Failed requests:        0
Total transferred:      1940 bytes
HTML transferred:       320 bytes
Requests per second:    658.24 [#/sec] (mean)
Time per request:       1.519 [ms] (mean)
Time per request:       1.519 [ms] (mean, across all concurrent requests)
 */