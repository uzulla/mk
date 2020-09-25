<?php

use Amp\Dns\Record;
use function Amp\Dns\resolve;

require_once ("vendor/autoload.php");

$time = microtime(true);
$promise =  Amp\Loop::run(function () {
    $request_list = [];
    /** @var Record[] $records */
    $request_list[] = yield resolve("github.com");
    $request_list[] = yield resolve("google.com");
    $request_list[] = yield resolve("yahoo.com");
    $request_list[] = yield resolve("facebook.com");

    $list = [];
    foreach($request_list as $records) {
        foreach ($records as $record) {
            $list[] = $record->getValue();
        }
    }
    var_dump($list);
});

echo "para". (microtime(true) - $time) ."sec".PHP_EOL;

$time = microtime(true);

// OSのキャッシュがきいちゃうので、gethostbyname速いｗ
gethostbyname("github.com");
gethostbyname("google.com");
gethostbyname("yahoo.com");
gethostbyname("facebook.com");

echo "single". (microtime(true) - $time) ."sec".PHP_EOL;

