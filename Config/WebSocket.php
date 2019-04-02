<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/22
 * Time: 18:14
 */
namespace Ccs\Config;

class WebSocket
{
    public static $gateway = [
        'host' => '127.0.0.1',
        'port' => '7272',
        'protocol' => 'ws',
    ];

    public static $task = [
        'host' => '127.0.0.1',
        'port' => '12345',
        'protocol' => 'Text'
    ];
}