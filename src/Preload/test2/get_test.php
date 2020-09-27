<?php

//  find store |grep php |xargs rm ; php generate.php

// php -dmemory_limit=2000M -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.enable_cli=1 memory_consumption=512 get_test.php

// php -dmemory_limit=2000M -dopcache.preload=preload.php -dopcache.memory_consumption=512 -dopcache.interned_strings_buffer=128 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080
// php -dmemory_limit=2000M -S 127.0.0.1:8080

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
// php -dmemory_limit=4000M -dopcache.memory_consumption=2000 -dopcache.interned_strings_buffer=500 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080

$ ab -n 10 http://127.0.0.1:8080/get_test.php
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /get_test.php
Document Length:        482062 bytes

Concurrency Level:      1
Time taken for tests:   7.175 seconds
Complete requests:      10
Failed requests:        9
   (Connect: 0, Receive: 0, Length: 9, Exceptions: 0)
Total transferred:      4822253 bytes
HTML transferred:       4820633 bytes
Requests per second:    1.39 [#/sec] (mean)
Time per request:       717.466 [ms] (mean)
Time per request:       717.466 [ms] (mean, across all concurrent requests)
Transfer rate:          656.37 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:   174  717 1705.5    179    5571
Waiting:      171  714 1705.3    176    5567
Total:        174  717 1705.5    179    5571

Percentage of the requests served within a certain time (ms)
  50%    179
  66%    180
  75%    180
  80%    181
  90%   5571
  95%   5571
  98%   5571
  99%   5571
 100%   5571 (longest request)


// php -dmemory_limit=4000M -dopcachpreload=preload.php -dopcache.e.memory_consumption=2000 -dopcache.interned_strings_buffer=500 -dopcache.revalidate_freq=0 -dopcache.max_accelerated_files=100000 -S 127.0.0.1:8080

$ ab -n 10 http://127.0.0.1:8080/get_test.php
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /get_test.php
Document Length:        482060 bytes

Concurrency Level:      1
Time taken for tests:   0.277 seconds
Complete requests:      10
Failed requests:        9
   (Connect: 0, Receive: 0, Length: 9, Exceptions: 0)
Total transferred:      4822234 bytes
HTML transferred:       4820614 bytes
Requests per second:    36.14 [#/sec] (mean)
Time per request:       27.667 [ms] (mean)
Time per request:       27.667 [ms] (mean, across all concurrent requests)
Transfer rate:          17021.17 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:    23   27   6.0     25      42
Waiting:       18   19   1.1     19      22
Total:         23   28   6.1     25      43

Percentage of the requests served within a certain time (ms)
  50%     25
  66%     26
  75%     29
  80%     33
  90%     43
  95%     43
  98%     43
  99%     43
 100%     43 (longest request)


// php -dmemory_limit=4000M -S 127.0.0.1:8080

$ ab -n 10 http://127.0.0.1:8080/get_test.php
This is ApacheBench, Version 2.3 <$Revision: 1879490 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient).....done


Server Software:
Server Hostname:        127.0.0.1
Server Port:            8080

Document Path:          /get_test.php
Document Length:        482062 bytes

Concurrency Level:      1
Time taken for tests:   39.846 seconds
Complete requests:      10
Failed requests:        2
   (Connect: 0, Receive: 0, Length: 2, Exceptions: 0)
Total transferred:      4822236 bytes
HTML transferred:       4820616 bytes
Requests per second:    0.25 [#/sec] (mean)
Time per request:       3984.600 [ms] (mean)
Time per request:       3984.600 [ms] (mean, across all concurrent requests)
Transfer rate:          118.19 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       0
Processing:  3632 3984 533.5   3768    5287
Waiting:     3626 3964 517.7   3754    5224
Total:       3632 3985 533.5   3768    5287

Percentage of the requests served within a certain time (ms)
  50%   3768
  66%   3880
  75%   4098
  80%   4505
  90%   5287
  95%   5287
  98%   5287
  99%   5287
 100%   5287 (longest request)

 */