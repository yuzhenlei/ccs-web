<?php
namespace Common\Lib;

class Console 
{
    public static function fatal($string)
    {
        fwrite(STDOUT, '[Fatal]: ' . $string . PHP_EOL);
    }

    public static function warning($string)
    {
        fwrite(STDOUT, '[Warning]: ' . $string . PHP_EOL);
    }

    public static function info($string)
    {
        fwrite(STDOUT, '[Info]: ' . $string . PHP_EOL);
    }
}
