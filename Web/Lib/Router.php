<?php
namespace Web\Lib;

use Common\Lib\Console;
use Web\Util\TextUtil;
use Web\Autoloader;
use Workerman\Protocols\Http;
use Exception;

class Router
{
    private static $routeBasePath = '';

    private static $routeNamespace = '';

    private static $routes = [];

    public static function setBasePath($path)
    {
        if (!is_string($path)) {
            Console::warning('Router::setRouteBasePath param is empty string');
        } else {
            self::$routeBasePath = $path;
            Autoloader::setRootPath(self::$routeBasePath);
        }
    }

    public static function setNamespace($namespace)
    {
        if (!is_string($namespace)) {
            Console::warning('Router::setRouteBasePath param is empty string');
        } else {
            self::$routeNamespace = $namespace;
        }
    }

    public static function register(array $routes)
    {
        foreach ($routes as $key => $route) {
            if (self::checkRoute($route) !== count($route)) {
                Console::info(self::checkRoute($route));
                Console::info(count($route));
                Console::info('Route register fail');
                return;
            }

            $uri = explode('/', TextUtil::filterSlash($route[0]));
            $routeRefer = &self::$routes[strtoupper($route[1])];
            foreach ($uri as $value) {
                $routeRefer = &$routeRefer[$value];
            }
            $routeRefer = $route;
            unset($routeRefer);
        }
    }

    private static function checkRoute($route)
    {
        $route = (array)$route;
        $index = 1;
        switch ($index) {
            case 1:  
                if ($index === 1) {
                    $item = current($route);
                    next($route);
                    if (is_string($item)) {
                        $index++;
                    } else {
                        Console::fatal('Please enter valid uri');
                        break;
                    }
                }
            case 2:
                if ($index === 2) {
                    $item = current($route);
                    next($route);
                    if (is_string($item) && in_array(strtoupper($item), ['GET', 'POST', 'DELETE', 'PUT'])) {
                        $index++;
                    } else {
                        Console::fatal('Please enter valid HTTP method');
                        break;
                    }
                }
            case 3:
                if ($index === 3) {
                    $item = current($route);
                    if (is_callable($item)) {
                        $index += 2;
                        next($route);
                        next($route);
                    } elseif (class_exists(self::getValidClassName($item))) {
                        $index++;
                    } else {
                        Console::fatal('Please enter valid class/callback: ' . self::getValidClassName($item));
                        break;
                    }
                }
            case 4:
                if ($index === 4) {
                    $class = current($route);
                    next($route);
                    $method = current($route);
                    next($route);
                    if (is_callable([self::$routeNamespace . $class, $method])) {
                        $index++;
                    } else {
                        Console::fatal('Please enter valid class method');
                        break;
                    }
                }
        }
        return --$index;
    }

    public static function locate($path, $method)
    {
        $uri = explode('/', $path);
        $routeRefer = &self::$routes[strtoupper($method)];
        foreach ($uri as $value) {
            if (isset($routeRefer[$value])) {
                $routeRefer = &$routeRefer[$value];
            } else {
                Http::header('HTTP', true, 404);
                try {
                    $content = View::display(__CCS_STATIC_PATH__ . '/404.html');
                } catch (Exception $e) {
                    $content = '<p style="text-align: center">Page Not Found</p>';
                }
                Http::end($content);
            }
        }
        return self::dispatch($routeRefer);
    }

    private static function dispatch($route)
    {
        $params = [];
        if (is_callable($route[2])) {
            $callable = $route[2];
        } else {
            $handleClass = self::getValidClassName($route[2]);
            $callable = [new $handleClass(), $route[3]];
        }
        return call_user_func_array($callable, $params);
    }

    private static function getValidClassName($class_name)
    {
        if (!is_string($class_name)) {
            return null;
        }
        if (substr(self::$routeNamespace, -1) != '\\') {
            $class_name = '\\' . $class_name;
        }
        return self::$routeNamespace . $class_name;
    }
}
