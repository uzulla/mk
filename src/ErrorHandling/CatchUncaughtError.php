<?php

# FatalErrorなどリカバリできないエラーをキャッチ
register_shutdown_function(function () {
    $error = error_get_last();
    if (!is_array($error) || !($error['type'] & (E_ERROR | E_PARSE | E_USER_ERROR | E_RECOVERABLE_ERROR))) {
        return; # 正常終了系
    }

    # 異常終了系

    # Logging un-excepted output buffer(debug|error messages)
    if (ob_get_status() > 0) {
        $something = ob_get_contents();
        if (strlen($something) > 0) {
            error_log("Unflushed ob: " . $something);
        }
        ob_end_clean();
    }

    # Error Logging
    error_log("Uncaught Fatal Error: {$error['type']}:{$error['message']} in {$error['file']}:{$error['line']}");

    # response error sample
    if (!headers_sent()) {
        http_response_code(500);
        header("Content-type: text/html");
    }
    echo "<h1>InternalServerError</h1>";
});
