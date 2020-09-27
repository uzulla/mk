<?php

//  find store |grep php |xargs rm ; php generate.php

// php -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.enable_cli=1 memory_consumption=512 get_test.php

// php -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.interned_strings_buffer=128 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080
// php -S 127.0.0.1:8080

$time = microtime(true);

// if preload enabled, don't load preload.
if(!function_exists("my_preloaded")) {
    require __DIR__ . "/preload.php";
}

echo "preload ". (microtime(true) - $time) . "sec".PHP_EOL;
$time = microtime(true);

$list = array_values(get_defined_functions()['user']);

echo "got_list ". (microtime(true) - $time) . "sec".PHP_EOL;
$time = microtime(true);

$filtered = array_filter($list, function($elem){
    if(strpos($elem, 'cache_func_') !==false ){
        return true;
    }else{
        return false;
    }
});

if(count($filtered)===0){
    throw new RuntimeException("any store found.");
}
var_export($filtered);
sort($filtered);
$func_name = array_pop($filtered);

echo "got_func_name ". (microtime(true) - $time) . "sec".PHP_EOL;
$time = microtime(true);


$val = call_user_func($func_name);

var_export($val);

echo "got_result ". (microtime(true) - $time) . "sec".PHP_EOL;

/*
$ ls store/|wc -l
   10000

// php -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.interned_strings_buffer=128 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /get_test.php
Document Length:        130 bytes

Concurrency Level:      1
Time taken for tests:   2.181 seconds
Complete requests:      100
Failed requests:        24
   (Connect: 0, Receive: 0, Length: 24, Exceptions: 0)
Total transferred:      29169 bytes
HTML transferred:       12969 bytes
Requests per second:    45.86 [#/sec] (mean)
Time per request:       21.805 [ms] (mean)
Time per request:       21.805 [ms] (mean, across all concurrent requests)
Transfer rate:          13.06 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:    19   22   1.6     21      30
Waiting:       19   21   1.6     21      30
Total:         19   22   1.6     21      30

Percentage of the requests served within a certain time (ms)
  50%     21
  66%     22
  75%     22
  80%     23
  90%     24
  95%     25
  98%     27
  99%     30
 100%     30 (longest request)

// php -S 127.0.0.1:8080
✓ 17:47:42 ~$ ab -n 100 http://127.0.0.1:8080/get_test.php
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /get_test.php
Document Length:        167 bytes

Concurrency Level:      1
Time taken for tests:   11.158 seconds
Complete requests:      100
Failed requests:        82
   (Connect: 0, Receive: 0, Length: 82, Exceptions: 0)
Total transferred:      32973 bytes
HTML transferred:       16773 bytes
Requests per second:    8.96 [#/sec] (mean)
Time per request:       111.578 [ms] (mean)
Time per request:       111.578 [ms] (mean, across all concurrent requests)
Transfer rate:          2.89 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:   102  111  44.8    105     538
Waiting:      101  111  44.7    104     537
Total:        102  112  44.8    105     539

Percentage of the requests served within a certain time (ms)
  50%    105
  66%    106
  75%    106
  80%    107
  90%    108
  95%    109
  98%    175
  99%    539
 100%    539 (longest request)

 */