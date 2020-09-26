<?php

use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

require_once(__DIR__ . "/vendor/autoload.php");

$client = new CurlHttpClient();

$time = microtime(true);

// パラレルテスト
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
    echo '.'; // "{$i} - length ". strlen($content).PHP_EOL;
}

echo PHP_EOL . "use parallel " . (microtime(true) - $time) . "sec" . PHP_EOL;

// パラレルしない直列テスト
$time = microtime(true);

for ($i = 0; $i < 379; ++$i) {
    $uri = "https://http2.akamai.com/demo/tile-$i.png";
    $content = file_get_contents($uri);
    echo '.'; // "{$i} - length ". strlen($content).PHP_EOL;
}

echo PHP_EOL . "use sync " . (microtime(true) - $time) . "sec" . PHP_EOL;

/* sample result

...........................................................................................................................................................................................................................................................................................................................................................................................
use parallel 0.48627400398254sec
...........................................................................................................................................................................................................................................................................................................................................................................................
use sync 18.528911113739sec

*/
