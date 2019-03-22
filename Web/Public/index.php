<?php

use Web\Lib\Request;
use Web\Lib\Router;
use Workerman\Protocols\Http;

//echo json_encode($_SERVER);
Request::extract();
//var_dump(Request::getUri());
Http::end(Router::locate(Request::getUri(), Request::getMethod()));


