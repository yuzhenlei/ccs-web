<?php

ini_set('display_errors', 'on');
use Workerman\Worker;
if(strpos(strtolower(PHP_OS), 'win') === 0)
{
    exit("start.php not support windows, please use start_for_win.bat\n");
}
// 检查扩展
if(!extension_loaded('pcntl'))
{
    exit("Please install pcntl extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
if(!extension_loaded('posix'))
{
    exit("Please install posix extension. See http://doc3.workerman.net/appendices/install-extension.html\n");
}
// 标记是全局启动
const GLOBAL_START = 1;
// 设置项目根路径
const __CCS_ROOT__ = __DIR__;

// 常量配置
// 系统Web路径
const __CCS_WEB_PATH__ = __CCS_ROOT__ . '/Web/';
// 系统Web下的静态文件页面路径
const __CCS_STATIC_PATH__ = __CCS_WEB_PATH__ . '/Public/static/';
// 服务注册中心 端口
const C_REGISTER_PORT = '1236';

require_once __DIR__ . '/vendor/autoload.php';

// 加载所有services/start*.php，以便启动所有服务
foreach(glob(__DIR__.'/Services/start*.php') as $start_file)
{
    require_once $start_file;
}
// 运行所有服务
Worker::runAll();
