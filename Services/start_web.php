<?php 
/**
 * This file is part of workerman.
 */
use \Workerman\Worker;
use \Workerman\WebServer;

require_once __DIR__ . '/../vendor/autoload.php';

// WebServer
$web = new WebServer("http://0.0.0.0:8080");
// WebServer进程数量
$web->count = 2;
$web->reusePort = true;
// 设置站点根目录
$web->addRoot('www.ccs.com', __CCS_ROOT__.'/Web/Public');

$web->onWorkerStart = function ($web) {
    require_once __CCS_ROOT__ . '/Web/Autoloader.php';
    foreach(glob(__CCS_ROOT__ . '/Lib/*.php') as $require_file)
    {
        require_once $require_file;
    }
    foreach(glob(__CCS_ROOT__ . '/Web/Config/*.php') as $require_file)
    {
        require_once $require_file;
    }
};


// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

