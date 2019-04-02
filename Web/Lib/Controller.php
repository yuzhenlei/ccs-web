<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/20
 * Time: 15:29
 */

namespace Ccs\Web\Lib;

use Ccs\Lib\CRedis;
use Workerman\Protocols\Http;
use Ccs\Lib\TcpConnection;
use Ccs\Config\RemoteKey;
use Ccs\Web\Lib\Request;

abstract class Controller
{
    /* @var Request */
    public $request;

    public function returnJson(array $data, $errno = 0, $message = 'Success')
    {
        return json_encode([
            'errno' => intval($errno),
            'message' => $message,
            'data' => $data
        ]);
    }

    public function auth()
    {
        $uid = $this->getUid();
        $token = $this->getToken();
        if (empty($token) || $token !== $this->getRedisToken($uid)){
            Http::end($this->returnJson([], 1403, 'Auth failed'));
        }
        return true;
    }

    protected function getRedisToken($uid)
    {
        return CRedis::cli()->hGet('u_'.$uid, 'token');
    }

    public function getUid()
    {
        return $this->request->getUid();
    }

    public function getToken()
    {
        return $this->request->getToken();
    }

    public function asyncTask($uri, $params)
    {
        $task = [
            'task' => $uri,
            'app_id' => 'web',
            'app_secret' => RemoteKey::$web['secret'],
            'params' => $params,
        ];
        // 调度异步任务，将此用户相关的数据预缓存
        TcpConnection::cli('task')->send(json_encode($task));
    }
}