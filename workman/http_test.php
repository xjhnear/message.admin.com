<?php  
ini_set('date.timezone','Asia/Shanghai');

use \Workerman\Worker;  
use \Workerman\Lib\Timer;  
use \Workerman\Connection\MysqlConnection; 
require_once '/Workerman/Autoloader.php';
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
		$minute = date('i');
		$hour = date('H');
		$day = date('d');
		$month = date('m');
		$dayofweek = date('w');
		$date_now_arr = array($minute,$hour,$day,$month,$dayofweek);
		//echo $minute.",".$hour.",".$day.",".$month.",".$dayofweek."\n";  
		$now_time = time();
		$db= new MysqlConnection('127.0.0.1', '3306', 'root', '','test');
		$all_tables=$db->select(array('id','crontab'))->from('timing')->where('cooldown < '.$now_time)->query();

		foreach ($all_tables as $item) {
			$can_send = true;
			$crontab_arr = explode(',',$item['crontab']);
			foreach ($crontab_arr as $k=>$v) {
				if($v <> '*' && $v <> $date_now_arr[$k]) {
					$can_send = false;
				}
			}
			if ($can_send) {
				$url = 'http://backend.cat.dev/api/web/autodata/timing_send';
				$data = array(
					'access-token'=>'admin',
					'id'=>$item['id']
				);
				$query_str = http_build_query($data);
				$info = parse_url($url);
				$fp = fsockopen($info["host"], 80, $errno, $errstr, 3);
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
			echo $can_send;
		}
    });  
};  
  
// 运行worker  
Worker::runAll();
