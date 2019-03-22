<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/20
 * Time: 15:29
 */

namespace Web\Lib;

use GatewayWorker\Lib\Gateway;

abstract class Controller
{
    public function returnJson(array $data, $errno = 0, $message = 'Success')
    {
        return json_encode([
            'errno' => intval($errno),
            'message' => $message,
            'data' => $data
        ]);
    }
}