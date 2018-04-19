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
    $time_interval = 30;
    Timer::add($time_interval, function()  
    {
		$now_time = time();
		$now_date = date('Y-m-d');
		$db= new MysqlConnection('127.0.0.1', '3306', 'root', 'root123A!','message_www');
		$all_tables=$db->select(array('message_id','message_code'))->from('yii2_message_list')->where('status = 1')->query();

		foreach ($all_tables as $item) {
			$db->update('yii2_message_list')->cols(array('status'=>5))->where('message_id='.$item['message_id'])->query();
			$url = 'http://47.100.111.70:5053/system/sms';
			$data = array(
				'access-token'=>'admin',
				'message_id'=>$item['message_id']
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
		}
		echo 'done!!';
    });  
};  
  
// 运行worker  
Worker::runAll();
