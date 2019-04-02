<?php

namespace Ccs\Web\Lib;

use Ccs\Lib\Console;
use Ccs\Web\Util\TextUtil;

class Request
{
    private $route = null;

    private $method = null;

    private $uri = null;

    private $contentType = null;

    private $contentLength = null;

    private $queryString = null;

    private $uid = null;

    private $token = null;

    private function __construct()
    {
        $this->setMethod($_SERVER['REQUEST_METHOD']);
        $this->setUri($_SERVER['REQUEST_URI']);
        if (in_array($this->getMethod(), ['POST', 'PUT'])) {
            $this->setContentType($_SERVER['CONTENT_TYPE'] ?: $_SERVER['HTTP_CONTENT_TYPE']);
            $this->setContentLength($_SERVER['CONTENT_LENGTH'] ?: $_SERVER['HTTP_CONTENT_LENGTH']);
        }
        $this->setQueryString($_SERVER['QUERY_STRING']);
        $this->setUid(isset($_SERVER['HTTP_CCS_AUTH_UID']) ? $_SERVER['HTTP_CCS_AUTH_UID'] : 0);
        $this->setToken(isset($_SERVER['HTTP_CCS_AUTH_TOKEN']) ? $_SERVER['HTTP_CCS_AUTH_TOKEN'] : null);
    }

    public static function instance()
    {
        $request = new self();
        return $request;
    }

    private function setMethod($string)
    {
        $this->method = strtoupper($string);
    }

    private function setUri($string)
    {
        $subStrEnd = stripos($string, '?');
        if ($subStrEnd !== false) {
            $string = substr($string, 0, $subStrEnd);
        }
        $this->uri = self::filterMultiSlash($string);
    }

    private function setQueryString($string)
    {
        parse_str($string, $this->queryString);
    }

    private function setContentType($string)
    {
        $this->contentType = $string;
    }

    private function setContentLength($string)
    {
        $this->contentLength = $string;
    }

    private function setUid($uid)
    {
        $this->uid = intval($uid);
    }

    private function setToken($token)
    {
        $this->token = $token;
    }

    public function setQueryParam($key, $value)
    {
        $this->queryString[$key] = $value;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function getContentLength()
    {
        return $this->contentLength;
    }

    public function getQueryString($key)
    {
        if (key_exists($key, $this->queryString)) {
            return $this->queryString[$key];
        }
        return null;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function bindRoute(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @return null|Route
     */
    public function getRoute()
    {
        return $this->route;
    }

    public function getUriSubPath()
    {
        return empty($this->uri) ? [''] : explode('/', $this->uri);
    }

    // 替换给定字符串中双/多'/'为单'/'，并且过滤两侧斜杠
    protected static function filterMultiSlash($string)
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
