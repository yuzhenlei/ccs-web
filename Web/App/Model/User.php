<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/19
 * Time: 19:13
 */
namespace Web\App\Model;

use Common\Lib\Console;
use GatewayWorker\Lib\Db;
use Common\Lib\CRedis;

class User
{
    public function test($username)
    {
        $db = Db::instance('ccs');
        $result = $db->select(['id'])->from('user')
            ->where([
                'username = :name' => $username
            ])
            ->row();
        return $result;
    }
}