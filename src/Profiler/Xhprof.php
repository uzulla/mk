<?php

// https://github.com/longxinH/xhprof
// git clone git@github.com:longxinH/xhprof.git
// php -S localhost:8899 -t xhprof/xhprof_html
// ssh -L 8899:localhost:8899 target

define("XHPROF_SLOW_SEC", 0); // secである、小数点OK。これ以下はxhprofに記録しない。0で全部保存

if (defined("XHPROF")) {
    define("XHPROF_START_TIME", microtime(true));
    require_once __DIR__ . '/xhprof/xhprof_lib/utils/xhprof_runs.php';
    xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}

register_shutdown_function(function () {
    if (defined("XHPROF")) {
        $consume_time = (microtime(true) - XHPROF_START_TIME);
        if ($consume_time > XHPROF_SLOW_SEC) {
            $data = xhprof_disable();
            $runs = new XHProfRuns_Default();
            $xhprof_id = preg_replace('|/|u', "／", $_SERVER['REQUEST_URI']);
            $xhprof_id = preg_replace('/[^a-zA-Z0-9／]/u', "-", $xhprof_id);
            $xhprof_id = $xhprof_id . "_use_" . sprintf("%d", ($consume_time * 1000)) . "ms_";
            $runs->save_run($data, $xhprof_id);
        }
    }
});
