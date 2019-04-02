<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/4/1
 * Time: 16:54
 */

namespace Ccs\Web\Lib;

use Ccs\Lib\Console;
use Exception;

class Route
{
    private $allowMethod = ['GET', 'POST', 'PUT', 'DELETE'];

    private $name = null;

    private $uri = null;

    private $method = null;

    private $controller = null;

    private $action = null;

    private $paramRegexp = [];

    private $subPath = [];

    public function __construct($route)
    {
        $this->setUri($route[0]);
        $this->setMethod($route[1]);
        $this->setController($route[2]);
        $this->setAction($this->controller, $route[3]);
        $this->setSubPath();
        $this->name = $this->method . $this->uri;
    }

    public function bindRegexp($name, $regexp)
    {
        if (!key_exists($name, $this->paramRegexp)) {
            throw new Exception("Bind regexp: $name is not exist");
        }
        if (!$this->isRegularExpression($regexp)) {
            throw new Exception("Bind regexp: '$regexp' is invalid");
        }
        $this->paramRegexp[$name] = $regexp;
        return $this;
    }

    private function isRegularExpression($string) {
        return @preg_match($string, '') !== false;
    }

    private function setUri($uri)
    {
        if (empty($uri) || !is_string($uri)) {
            throw new Exception("Route uri '$uri' is invalid");
        }
        $this->uri = trim($uri, '/');
    }

    private function setMethod($method)
    {
        $method = strtoupper($method);
        if (!in_array($method, $this->allowMethod)) {
            throw new Exception("Route method '$method' is invalid");
        }
        $this->method = $method;
    }

    private function setController($controller)
    {
        if (!is_callable($controller)) {
            if (!class_exists($controller)) {
                throw new Exception("Route controller '$controller' is invalid");
            }
        }
        $this->controller = $controller;
    }

    private function setAction($controller, $action)
    {
        if (is_callable($controller)) {
            $this->action = null;
        } else {
            $this->action = $action;
        }
    }

    // 函数setSubPath将路由表的uri以'/'分隔成一个各子路径组成的数组，
    // 每个数组项包含isNamePath和name两个值.
    // isNamePath，表示该子路径是否为用户使用{name}定义的命名参数
    // name，若isNamePath为true则该值为{name}中的name，否则为子路径的名字
    private function setSubPath()
    {
        $uri = empty($this->uri) ? [''] : explode('/', $this->uri);
        foreach ($uri as $subPath) {
            $matchCount = preg_match('/^{([\w\-]+)}$/', $subPath, $matches);
            $this->subPath[] = [
                'isNamePath' => $matchCount ? true : false,
                'name' => $matchCount ? $matches[1] : $subPath,
            ];
            if ($matchCount) {
                $this->paramRegexp[$matches[1]] = $this->getDefRegexp();
            }
        }
    }

    private function getDefRegexp()
    {
        return '/^.+?$/';
    }

    public function getParamRegexp($name)
    {
        return $this->paramRegexp[$name];
    }

    public function getSubPath()
    {
        return $this->subPath;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getUri()
    {
        return $this->uri;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }
}