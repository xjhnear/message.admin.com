<?php  
ini_set('date.timezone','Asia/Shanghai');

use \Workerman\Worker;  
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
		$minute = date('i');
		$hour = date('H');
		$day = date('d');
		$month = date('m');
		$dayofweek = date('w');
		$date_now_arr = array($minute,$hour,$day,$month,$dayofweek);
		//echo $minute.",".$hour.",".$day.",".$month.",".$dayofweek."\n";  
		$now_time = time();
		$now_date = date('Y-m-d');
		$db= new MysqlConnection('192.168.20.104', '3306', 'root', 'cat123','yii2-peng');
		$all_tables_1=$db->select(array('id','crontab'))->from('timing')->where('status = 1')->where('isdel = 0')->where('cooldown < '.$now_time)->where('end_date = "" ')->query();
		$all_tables_2=$db->select(array('id','crontab'))->from('timing')->where('status = 1')->where('isdel = 0')->where('cooldown < '.$now_time)->where('end_date > "'.$now_date.'"')->query();
		$all_tables = array_merge($all_tables_1,$all_tables_2);

		foreach ($all_tables as $item) {
			$can_send = true;
			$crontab_arr = explode(' ',$item['crontab']);
			foreach ($crontab_arr as $k=>$v) {
				$v = explode(',',$v);
				if(!in_array('*',$v) && !in_array($date_now_arr[$k],$v)) {
					$can_send = false;
				}
			}
			if ($can_send) {
				$url = 'http://192.168.20.104:8220/api/web/autodata/timing_send';
				$data = array(
					'access-token'=>'admin',
					'id'=>$item['id']
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
			echo $can_send;
		}
    });  
};  
  
// 运行worker  
Worker::runAll();
