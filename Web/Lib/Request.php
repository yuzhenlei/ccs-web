<?php
namespace Web\Lib;

use Web\Util\TextUtil;

class Request
{
    private static $method = null;

    private static $uri = null;

    private static $contentType = null;

    private static $contentLength = null;

    private static $queryString = null;

    public static function extract()
    {
        self::setMethod($_SERVER['REQUEST_METHOD']);
        self::setUri($_SERVER['REQUEST_URI']);
        if (in_array(self::getMethod(), ['POST', 'PUT'])) {
            self::setContentType($_SERVER['CONTENT_TYPE'] ?: $_SERVER['HTTP_CONTENT_TYPE']);
            self::setContentLength($_SERVER['CONTENT_LENGTH'] ?: $_SERVER['HTTP_CONTENT_LENGTH']);
        }
        self::setQueryString($_SERVER['QUERY_STRING']);
    }

    private static function setMethod($string)
    {
        self::$method = strtoupper($string);
    }
    private static function setUri($string)
    {
        $subStrEnd = stripos($string, '?');
        if ($subStrEnd !== false) {
            $string = substr($string, 0, $subStrEnd);
        }
        self::$uri = TextUtil::filterSlash($string);
    }
    private static function setQueryString($string) 
    {
        $string = substr($string, stripos($string, '?') + 1);
        self::$queryString = explode('&', $string);
    }
    private static function setContentType($string)
    {
        self::$contentType = $string;
    }
    private static function setContentLength($string)
    {
        self::$contentLength = $string;
    }
    public static function getMethod()
    {
        return self::$method;
    }
    public static function getUri()
    {
        return self::$uri;
    }
    public static function getContentType()
    {
        return self::$contentType;
    }
    public static function getContentLength()
    {
        return self::$contentLength;
    }
    public static function getQueryString($key)
    {
        return self::$queryString[$key];
    }
}
