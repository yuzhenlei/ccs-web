<?php
/**
 * Created by PhpStorm.
 * User: yuzhenlei
 * Date: 2019/4/1
 * Time: 16:52
 */

namespace Ccs\Web\Lib;

use Ccs\Lib\Console;

class RouteCollection
{
    // Collection name.
    private $name;

    private $routes = [];

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * @param Route $route
     */
    public function addRoute(Route $route)
    {
        $this->routes[$route->getMethod()][$route->getUri()] = $route;
    }

    /**
     * @param Request $request
     * @return Route|null
     */
    public function match(Request $request)
    {
        $requestMethod = $request->getMethod();
        // $subUri is array that Request::uri separated by '/'
        $requestUriSubPath = $request->getUriSubPath();
        $isMatch = false;
        /* @var $route Route */
        foreach ($this->routes[$requestMethod] as $key => $route) {
            $idx = 0;
            $subPath = $route->getSubPath();
            $matchParams = [];
            if (count($subPath) !== count($requestUriSubPath)) {
                continue;
            }
            foreach ($subPath as $sub) {
                if ($sub['isNamePath']) {
                    if (!preg_match($route->getParamRegexp($sub['name']), $requestUriSubPath[$idx], $matches)) {
                        break;
                    }
                    $matchParams[$sub['name']] = $matches[0];
                } elseif ($sub['name'] !== $requestUriSubPath[$idx]) {
                    break;
                }
                $idx++;
            }
            if ($idx === count($requestUriSubPath)) {
                $isMatch = true;
                break;
            }
        }
        if ($isMatch) {
            foreach ($matchParams as $key => $value) {
                $request->setQueryParam($key, $value);
            }
            return $route;
        }
        return null;
    }
}