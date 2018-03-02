<?php  
ini_set('date.timezone','Asia/Shanghai');

use \Workerman\Worker;
use \Workerman\Lib\Timer;
require_once __DIR__ . '/Workerman/Autoloader.php';
require_once __DIR__ . '/PHPMailer/PHPMailerAutoload.php';
  
$task = new Worker();  
// 开启多少个进程运行定时任务，注意多进程并发问题  
$task->count = 1;  
$task->onWorkerStart = function($task)  
{  
    // 每30秒执行一次 支持小数，可以精确到0.001，即精确到毫秒级别
    $time_interval = 5;
    Timer::add($time_interval, function()  
    {
        echo '123123';
    });  
};  
  
// 运行worker  
Worker::runAll();
