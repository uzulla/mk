<?php

$code = '<?php $GLOBALS["time"]=time(); ' . PHP_EOL;
for ($i = 0; $i < 100000; $i++) {
    $code .= 'function nonsense_' . (string)time() . '_' . $i . '($a){echo $a.time();}' . PHP_EOL;
}

file_put_contents(__DIR__ . "/store/" . time() . ".php", $code);
