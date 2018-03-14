<?php  
ini_set('date.timezone','Asia/Shanghai');

use \Workerman\Worker;
//use Clue\React\Redis\Factory;
//use Clue\React\Redis\Client;
use \Workerman\Connection\RedisDb;
use \Workerman\Config\Db;
use \Workerman\Lib\Timer;  
use \Workerman\Connection\MysqlConnection; 
require_once __DIR__ . '/Workerman/Autoloader.php';
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
  
$task = new Worker();  
// 开启多少个进程运行定时任务，注意多进程并发问题  
$task->count = 1;  
$task->onWorkerStart = function($task)  
{  
    // 每30秒执行一次 支持小数，可以精确到0.001，即精确到毫秒级别
    $time_interval = 60;
    Timer::add($time_interval, function()  
    {
        $url = 'http://47.100.101.44:5057/system/call';
        $data = array(
            'access-token'=>'admin'
        );
        $query_str = http_build_query($data);
        $info = parse_url($url);
        $fp = fsockopen($info["host"], $info["port"], $errno, $errstr, 3);
        //$head = "GET ".$info['path']."?".$info["query"]." HTTP/1.0\r\n";
        $head = "GET ".$info['path']."?".$query_str." HTTP/1.0\r\n";
        $head .= "Host: ".$info['host']."\r\n";
        $head .= "\r\n";
        fputs($fp, $head);
        fclose($fp);
//				while (!feof($fp))
//				{
//					$line = fread($fp,4096);
//					echo $line;
//				}
        echo 'done!!';
    });  
};  
  
// 运行worker  
Worker::runAll();
