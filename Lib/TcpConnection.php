<?php
namespace Ccs\Lib;

use Workerman\Connection\AsyncTcpConnection;
use Ccs\Config\WebSocket as WsConfig;
use Exception;

/**
 * Class TcpConnection
 *
 * 维持一个与remote service通讯的连接
 *
 * @package Web\Lib
 */
class TcpConnection
{
    /* @var AsyncTcpConnection */
    private static $connection = [];

    /**
     * @param $config
     * @return AsyncTcpConnection
     */
    private static function connect($worker)
    {
        if (!isset(WsConfig::$$worker)) {
            echo "\\Web\\Config\\WebSocket::$worker not set\n";
            throw new Exception("\\Web\\Config\\WebSocket::$worker not set\n");
        }
        $config = WsConfig::$$worker;
        try {
            self::$connection[$worker] = new AsyncTcpConnection($config['protocol'].'://'.$config['host'].':'.$config['port']);
        } catch (\Exception $e) {
            Console::warning('AsyncTcpConnection connect failed: '.$e->getMessage());
        }
        // 连上后发送hello字符串
        self::$connection[$worker]->onConnect = function($connection){
            Console::info('AsyncTcpConnection connect succeeded');
        };
        // 连接上发生错误时，一般是连接远程websocket服务器失败错误
        self::$connection[$worker]->onError = function($connection, $code, $msg){
            Console::warning('AsyncTcpConnection occur error');
        };
        // 当连接远程websocket服务器的连接断开时
        self::$connection[$worker]->onClose = function($connection){
            Console::info('AsyncTcpConnection close succeeded');
        };
        self::$connection[$worker]->connect();
    }

    /**
     * @param $worker
     * @return AsyncTcpConnection
     * @throws Exception
     */
    public static function cli($worker)
    {
        if (empty(self::$connection[$worker])) {
            self::connect($worker);
        }

        return self::$connection[$worker];
    }
}