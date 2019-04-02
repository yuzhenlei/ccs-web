<?php
namespace Ccs\Lib;

class Console 
{
    private static $color = [
        '<reset>' => "\e[0m",
        '<default>' => "\e[39m",
        '<red>' => "\e[31m",
        '<light-red>' => "\e[91m",
        '<yellow>' => "\e[33m",
        '<light-yellow>' => "\e[93m",
        '<white>' => "\e[97m",
        '<light-gray>' => "\e[37m",
    ];

    public static function fatal($string)
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        self::out("<light-red>[Fatal]: $string<reset>" . PHP_EOL);
    }

    public static function warning($string)
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        self::out("<light-yellow>[Warning]: $string<reset>" . PHP_EOL);
    }

    public static function info($string)
    {
        if (is_array($string)) {
            $string = json_encode($string);
        }
        self::out("<light-gray>[Info]: $string<reset>" . PHP_EOL);
    }

    private static function out($string)
    {
        $string = str_replace(array_keys(self::$color), array_values(self::$color), $string);
        fwrite(STDOUT, $string);
    }
}
