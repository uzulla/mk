<?php

// $logger = new \Monolog\Logger("App");
// $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
// $stream = new \Monolog\Handler\StreamHandler("/home/isucon/app.log", \Monolog\Logger::DEBUG);
// $stream->setFormatter(new \Torb\HumanReadableFormatter());
// $logger->pushHandler($stream);

{ //=====================
    function getAccessId(): string
    {
        $str = $_SERVER['REQUEST_METHOD']. "_" .$_SERVER['REQUEST_URI'];
        $str = preg_replace("|/|u", "／", $str);
        $str = preg_replace("|:|u", "：", $str);
        $str = preg_replace("|\&|u", "＆", $str);
        $str = preg_replace("|\\|u", "＼", $str);
        $str = preg_replace("|\?.&\z|u", "？", $str);
        return $str;
    }
} //=====================
