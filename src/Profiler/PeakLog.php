<?php
define("START_AT", microtime(true));
// 基本のログ設定（後で上書きされる（ロガーが別にある）こともあるので注意
define("REQUEST_ID", base64_encode(microtime(true)));

// .....

error_log("pklog: " . (string)ceil((microtime(true) - START_AT) * 1000) . "ms " . (string)(memory_get_peak_usage() / 1024) . "kb");
