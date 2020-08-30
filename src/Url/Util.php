<?php

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
