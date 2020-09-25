<?php

use Amp\Http\Client\HttpClientBuilder;
use Amp\Http\Client\Request;
use Amp\Http\Client\Response;

require_once("vendor/autoload.php");

$time = microtime(true);

Amp\Loop::run(function () {
    $client = HttpClientBuilder::buildDefault();

    $uris = [
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
        "https://speakerdeck.com/uzulla",
    ];

    $requestHandler = static function (string $uri) use ($client): \Generator {
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
            print $uri . " - " . \strlen($body) . " bytes" . PHP_EOL;
        }
    } catch (HttpException $error) {
        // If something goes wrong Amp will throw the exception where the promise was yielded.
        // The HttpClient::request() method itself will never throw directly, but returns a promise.
        echo $error;
    }
});

echo "para" . (microtime(true) - $time) . "sec" . PHP_EOL;

$time = microtime(true);

file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");
file_get_contents("https://speakerdeck.com/uzulla");

echo "sync" . (microtime(true) - $time) . "sec" . PHP_EOL;

$time = microtime(true);

file_get_contents("https://speakerdeck.com/uzulla");

echo "oneshot" . (microtime(true) - $time) . "sec" . PHP_EOL;
