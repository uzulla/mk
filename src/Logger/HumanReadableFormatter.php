<?php

namespace Torb;

// A wrapper class for Environment Variables
use Monolog\Formatter\FormatterInterface;

class HumanReadableFormatter implements FormatterInterface
{

    public function format(array $record)
    {
//        return print_r($record, true) . PHP_EOL;
        return REQUEST_ID ."/". $record['message'] . PHP_EOL . print_r($record['context'],true) . PHP_EOL;
    }

    public function formatBatch(array $records)
    {
        $str = "";
        foreach($records as $record){
            // todo context がないなら、出力しないとかがよいのではないか
            $str .= REQUEST_ID."/" . $record['message'] . PHP_EOL . print_r($record['context'],true) . PHP_EOL;
        }
        return $str . PHP_EOL;
    }
}

/*
Array
(
    [message] => no session
    [context] => Array
        (
        )

    [level] => 300
    [level_name] => WARNING
    [channel] => isucari
    [datetime] => DateTime Object
        (
            [date] => 2020-08-05 10:18:00.365372
            [timezone_type] => 3
            [timezone] => UTC
        )

    [extra] => Array
        (
            [uid] => 0c6bfe0
        )

) */

