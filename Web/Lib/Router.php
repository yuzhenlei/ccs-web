<?php

namespace Ccs\Web\Lib;

use Ccs\Lib\Console;
use Ccs\Web\Util\TextUtil;
use Workerman\Protocols\Http;
use Exception;


class Router
{
    /* @var RouteCollection */
    private static $routeCollection = null;

    /**
     * @param array $routeConfig
     * @return Route
     */
    public static function register(array $routeConfig)
    {
        $route = new Route($routeConfig);
        self::collection()->addRoute($route);
        return $route;
    }

    public static function locate(Request $request)
    {
        $route = self::$routeCollection->match($request);
        if (!$route) {
            Http::header('HTTP', true, 404);
            try {
                $content = View::display(__CCS_STATIC_PATH__ . '/404.html');
            } catch (Exception $e) {
                $content = '<p style="text-align: center">Page Not Found</p>';
            }
            Http::end($content);
        }
        $request->bindRoute($route);
        return self::dispatch($request);
    }

    private static function dispatch(Request $request)
    {
        $params = [];
        if (is_callable($request->getRoute()->getController())) {
            $callable = $request->getRoute()->getController();
        } else {
            $handleClass = $request->getRoute()->getController();
            $instance = new $handleClass();
            $instance->request = $request;
            $callable = [$instance, $request->getRoute()->getAction()];
        }
        return call_user_func_array($callable, $params);
    }

    private static function collection()
    {
        if (!self::$routeCollection) {
            self::$routeCollection = new RouteCollection('web');
        }
        return self::$routeCollection;
    }
}