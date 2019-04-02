<?php
namespace Ccs\Web\Config;

use Ccs\Web\Lib\Router;

// [uri, http-method, controller | callable, method | null]

// uri: www.example.com
Router::register(['/', 'GET', \Ccs\Web\App\Controller\Hello::class, 'world']);
// uri: www.example.com/hello/tom
Router::register(['hello/{name}', 'GET', \Ccs\Web\App\Controller\Hello::class, 'world']);