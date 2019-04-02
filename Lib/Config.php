<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/30
 * Time: 15:17
 */

namespace Ccs\Lib;

class Config
{
    private static $_configs = [];

    public static function import(array $items) {
        self::$_configs = array_merge(self::$_configs, $items);
        return true;
    }

    public static function set($key, $value = null)
    {
        if (is_string($key)) {
            self::$_configs[$key] = $value;
        }
        if (is_array($key)) {
            self::$_configs = array_merge(self::$_configs, $key);
        }
        return true;
    }

    public static function get($key) {
        if (key_exists($key, self::$_configs)) {
            return self::$_configs[$key];
        }
        return null;
    }
}