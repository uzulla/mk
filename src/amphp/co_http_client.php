<?php

use Amp\Delayed;
use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;
use Amp\Loop;
use function Amp\asyncCall;

require_once("vendor/autoload.php");

$time = microtime(true);

$result = [];

// async
asyncCall(function () use (&$result){
    $client = HttpClientBuilder::buildDefault();

    $uris = [];

    for ($i = 0; $i < 379; ++$i) {
        $uris[] = "https://http2.akamai.com/demo/tile-$i.png";
    }

    $requestHandler = static function (string $uri) use ($client): Generator {
        /** @var Response $response */
        $response = yield $client->request(new Request($uri));
        return yield $response->getBody()->buffer();
    };

    try {
        $promises = [];

        foreach ($uris as $uri) {
            $promises[$uri] = Amp\call($requestHandler, $uri);
        }

        $bodies = yield $promises;

        foreach ($bodies as $uri => $body) {
            echo ".";
            $result[] = $uri . " - " . \strlen($body) . " bytes" . substr($body, 0, 30) . PHP_EOL;
        }
    } catch (HttpException $error) {
        echo $error;
    }
});

asyncCall(function (){ // just add another coroutine.
    for ($i = 0; $i < 3; ++$i) {
        echo "+";
        yield new Delayed(100);
    }
});

echo PHP_EOL. "loop registered" . (microtime(true) - $time) . "sec" . PHP_EOL;

Loop::run(); // wait all

// var_dump($result); // result set

echo PHP_EOL. "para finish" . (microtime(true) - $time) . "sec" . PHP_EOL;

// sync
$time = microtime(true);

for ($i = 0; $i < 379; ++$i) {
    $uri = "https://http2.akamai.com/demo/tile-$i.png";
    $body = file_get_contents($uri);
    echo "."; //$uri . " - " . \strlen($body) . " bytes" . substr($body, 0, 30) . PHP_EOL;
}

echo "sync finish" . (microtime(true) - $time) . "sec" . PHP_EOL;

