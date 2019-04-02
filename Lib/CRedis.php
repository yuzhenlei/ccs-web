<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/13
 * Time: 10:29
 */

namespace Ccs\Lib;

use Redis;

class CRedis
{
    /* @var Redis */
    private static $redisCli = null;

    private static function connect()
    {
        self::$redisCli = new Redis();
        self::$redisCli->connect(Config::get('redis_host'), Config::get('redis_port'), 3);
    }

    /**
     * @return Redis
     */
    public static function cli()
    {
        if (!self::$redisCli) {
            self::connect();
        }
        return self::$redisCli;
    }
}