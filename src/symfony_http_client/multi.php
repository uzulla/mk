<?php

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once (__DIR__."/vendor/autoload.php");

$client = new CurlHttpClient();

$time = microtime(true);

$responses = [];
for ($i = 0; $i < 379; ++$i) {
    $uri = "https://http2.akamai.com/demo/tile-$i.png";
    try {
        $responses[] = $client->request('GET', $uri);
    } catch (TransportExceptionInterface $e) {
        echo "got some error: {$e->getMessage()}";
    }
}

$i = 0;
foreach ($responses as $response) {
    $i++;
    $content = $response->getContent();
    echo "{$i} - length ". strlen($content).PHP_EOL;
}

echo "use parallel" . (microtime(true) - $time) . "sec" . PHP_EOL;

for ($i = 0; $i < 379; ++$i) {
    $uri = "https://http2.akamai.com/demo/tile-$i.png";
    $content = file_get_contents($uri);
    echo "{$i} - length ". strlen($content).PHP_EOL;
}

echo "use sync" . (microtime(true) - $time) . "sec" . PHP_EOL;
