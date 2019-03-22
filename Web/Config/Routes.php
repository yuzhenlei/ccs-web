<?php

use Web\Lib\Router;

Router::setBasePath(__CCS_ROOT__ . '/Web/');

Router::setNamespace('\\Web\\App\\Controller\\');

Router::register([
    // [uri, http-method, controller | callable, method | null]
    ['/', 'GET', 'Hello', 'world'],
    ['hello', 'GET', 'Hello', 'world'],
]);