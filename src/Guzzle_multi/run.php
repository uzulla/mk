<?php

use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once(__DIR__ . "/vendor/autoload.php");

$time = microtime(true);

$client = new Client([]);

$promises = [];

for ($i = 0; $i < 379; ++$i) { // 379 指定すると Too many open filesする。 ulimit -n 200000 とかで回避はできる
    $uri = "https://http2.akamai.com/demo/tile-$i.png";
    try {
        $promise = $client->requestAsync('GET', $uri);

        $promise->then(
            function ($response) {
                echo ".";
                if ($response->getStatusCode() !== 200) {
                    throw new Exception("status code is not 200");
                }
                return $response;
            },
            // onRejected
            function ($reason) {
                error_log($reason);
                throw new Exception("status code is not 200");
            }
        );

        $promises[] = $promise;

    } catch (TransportExceptionInterface $e) {
        echo "got some error: {$e->getMessage()}";
    }
}

$results = Promise\all($promises)->wait();

foreach ($results as $result) {
    $contents = $result->getBody()->getContents();
}

echo PHP_EOL . "use parallel " . (microtime(true) - $time) . "sec" . PHP_EOL;

/*
...........................................................................................................................................................................................................................................................................................................................................................................................
use parallel 3.6906480789185sec

- Symfony http clientより遅い。(symfony は  0.44614791870117sec、もちろん普通のsyncなfile_get_contentsよりははやい)
- 色々凝ったことをしているので、Poolつかわないと簡単にtoo many open fileするっぽい
 */
