<?php

// generate tons of files.
for ($i = 0; $i < 10000; $i++) {
    $code = '<?php '.PHP_EOL.'function cache_func_' . microtime(true)*10000 . '_' . $i . '(){ return [1,2,3];}' . PHP_EOL;
    file_put_contents(__DIR__ . "/store/" . microtime(true)*10000 . '_' . $i . ".php", $code);
}

