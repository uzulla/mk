<?php

# enable log
error_reporting(E_ALL);
ini_set("log_errors", "on");
ini_set("error_log", "php://stderr");

# display_error
ini_set("display_errors", "0");
ini_set("display_startup_errors", "0");
ini_set('html_errors', "0");

# xdebug
ini_set('xdebug.var_display_max_children', -1);
ini_set('xdebug.var_display_max_data', -1);
ini_set('xdebug.var_display_max_depth', -1);

# ログ出力調整
ini_set("log_errors_max_len", "1048576"); # 1 * 1024 * 1024 = 1MB
