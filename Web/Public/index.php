<?php

use Ccs\Web\Lib\Request;
use Ccs\Web\Lib\Router;
use Workerman\Protocols\Http;

//开启session
//Http::sessionStart();
$request = Request::instance();
Http::end(Router::locate($request));


