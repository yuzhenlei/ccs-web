<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/23
 * Time: 22:53
 */

namespace Ccs\Config;

/*
 * 类名为Db;
 */
class DbExample
{
    // 每个静态属性名即数据库名
    public static $ccs = [
        'host' => '127.0.0.1',
        'port' => 3306,
        'user' => 'ccs',
        'password' => '',
        'dbname' => 'ccs',
        'charset' => 'utf8mb4',
    ];
}