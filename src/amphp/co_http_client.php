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

asyncCall(function () use (&$result){
    $client = HttpClientBuilder::buildDefault();

    $uris = [
        "https://speakerdeck.com/uzulla?1",
        "https://speakerdeck.com/uzulla?2",
        "https://speakerdeck.com/uzulla?3",
        "https://speakerdeck.com/uzulla?4",
        "https://speakerdeck.com/uzulla?5",
    ];

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
            echo "-";
            $result[] = $uri . " - " . \strlen($body) . " bytes" . substr($body, 0, 30) . PHP_EOL;
        }
    } catch (HttpException $error) {
        echo $error;
    }
});

asyncCall(function () use (&$result){
    $client = HttpClientBuilder::buildDefault();

    $uris = [
        "http://cfe.jp/?1",
        "http://cfe.jp/?2",
        "http://cfe.jp/?3",
        "http://cfe.jp/?4",
        "http://cfe.jp/?5",
    ];

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

asyncCall(function (){
    yield new Delayed(2000);
});

echo "registered" . (microtime(true) - $time) . "sec" . PHP_EOL;

Loop::run(); // wait all

var_dump($result);

echo "para finish" . (microtime(true) - $time) . "sec" . PHP_EOL;
