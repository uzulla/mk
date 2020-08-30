<?php

# Noticeを含むすべてのエラーをキャッチしてExceptionに変換
set_error_handler(function (int $severity, string $message, string $file, int $line) {
    /** @noinspection PhpUnhandledExceptionInspection */
    throw new ErrorException($message, 0, $severity, $file, $line);
});
