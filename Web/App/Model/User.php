<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/19
 * Time: 19:13
 */
namespace Ccs\Web\App\Model;

use Ccs\Lib\Console;
use Ccs\Lib\Db;
use Ccs\Lib\CRedis;

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