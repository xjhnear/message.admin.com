<?php  
ini_set('date.timezone','Asia/Shanghai');

use \Workerman\Worker;
use Clue\React\Redis\Factory;
use Clue\React\Redis\Client;
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
    $time_interval = 5;
    Timer::add($time_interval, function()  
    {

        $all_tables_1 = RedisDb::instance('redis')->get('isp_1391743');
        print_r($all_tables_1);exit;

        $all_tables_1 = RedisDb::instance('redis')->get('isp_139174311');
        print_r($all_tables_1);exit;


        $now_time = time();
        $now_date = date('Y-m-d');
        $db= new MysqlConnection('127.0.0.1', '3306', 'root', 'near','message_www');
        $all_tables=$db->select(array('message_id','phonenumbers'))->from('yii2_message_list')->where('phonenumbers_json = ""')->query();
        foreach ($all_tables as $item) {
            $mobile_arr = explode(',', $item['phonenumbers']);
            $phone_number_arr = $phone_number_show = array();
            $phone_number_arr['unicom'] = $phone_number_arr['mobile'] = $phone_number_arr['telecom'] = $phone_number_arr['other'] = array();
            foreach ($mobile_arr as $item_phonenumber) {
                $phone_number_7 =  substr($item_phonenumber,0,7);
                if (Redis::exists("isp_".$phone_number_7)) {
                    $operator = Redis::get("isp_".$phone_number_7);
                } else {
                    $operator = '';
                }
                switch ($operator) {
                    case "联通":
                        $phone_number_arr['unicom'][] = $item_phonenumber;
                        break;
                    case "移动":
                        $phone_number_arr['mobile'][] = $item_phonenumber;
                        break;
                    case "电信":
                        $phone_number_arr['telecom'][] = $item_phonenumber;
                        break;
                    case "虚拟/联通":
                        $phone_number_arr['unicom'][] = $item_phonenumber;
                        break;
                    case "虚拟/移动":
                        $phone_number_arr['mobile'][] = $item_phonenumber;
                        break;
                    case "虚拟/电信":
                        $phone_number_arr['telecom'][] = $item_phonenumber;
                        break;
                    default:
                        $phone_number_arr['other'][] = $item_phonenumber;
                        break;
                }
                $phone_number_show = array_merge($phone_number_arr['unicom'],$phone_number_arr['mobile'],$phone_number_arr['telecom'],$phone_number_arr['other']);


            }
        }


        $all_tables_1 = RedisDb::instance('redis')->get('isp_1391743');
		print_r($all_tables_1);exit;

        $url = 'http://47.100.101.44:5057/system/status';
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
    });  
};  
  
// 运行worker  
Worker::runAll();
