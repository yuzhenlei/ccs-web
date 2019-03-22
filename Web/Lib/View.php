<?php
namespace Web\Lib;

use Exception;

/**
 * Class View
 *
 * 用于返回静态页面的内容
 *
 * @package Web\Lib
 */
class View
{
    private static $templateCache = [];

    /**
     * @param $file_path
     * @return bool|mixed|string
     * @throws Exception
     */
    public static function display(string $file_path, $isCache = true)
    {
        if ($isCache && isset(self::$templateCache[$file_path])) {
            return self::$templateCache[$file_path];
        }
        if (!file_exists($file_path)) {
            throw new Exception("File: {$file_path} is not exist");
        }
        $content = file_get_contents($file_path);
        if ($content === false) {
            throw new Exception("File: {$file_path} open failed");
        }
        if ($isCache) {
            self::$templateCache[$file_path] = $content;
        }
        return $content;
    }
}