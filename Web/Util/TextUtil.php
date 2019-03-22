<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/3/19
 * Time: 16:42
 */
namespace Web\Util;

class TextUtil
{
    // 去除给定字符串中双/多斜杠，两侧斜杠
    public static function filterSlash($string)
    {
        if (empty($string)) {
            return $string;
        }
        do {
            $string = str_replace('//', '/', $string);
        } while (strpos($string, '//') !== false);
        $string = trim($string, '/');
        return $string;
    }
}