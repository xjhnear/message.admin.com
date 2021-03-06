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
        $now_time = time();
        $now_date = date('Y-m-d');
        $db= new MysqlConnection('127.0.0.1', '3306', 'root', 'root123A!','message_www');
        $all_tables=$db->query('SELECT a.message_id,phonenumbers,message_code,content,send_time,create_uid FROM yii2_message_list a INNER JOIN yii2_message_list_detail b ON a.message_id=b.message_id WHERE b.phonenumbers_json = ""');
        foreach ($all_tables as $item) {
            $mobile_arr = explode(',', $item['phonenumbers']);
            $phone_number_arr = $phone_number_show = array();
            $phone_number_arr['unicom'] = $phone_number_arr['mobile'] = $phone_number_arr['telecom'] = $phone_number_arr['other'] = array();
            $sql="INSERT INTO yii2_message_detail (phonenumber,message_id,message_code,content,send_time,operator,create_uid,create_time) VALUES";
            $i = 0;
            foreach ($mobile_arr as $item_phonenumber) {
                $phone_number_7 =  substr($item_phonenumber,0,7);
                if (RedisDb::instance('redis')->get("isp_".$phone_number_7)) {
                    $operator = RedisDb::instance('redis')->get("isp_".$phone_number_7);
                } else {
                    $operator = '';
                }
                switch ($operator) {
                    case "联通":
                        $operator_code = 1;
                        $phone_number_arr['unicom'][] = $item_phonenumber;
                        break;
                    case "移动":
                        $operator_code = 2;
                        $phone_number_arr['mobile'][] = $item_phonenumber;
                        break;
                    case "电信":
                        $operator_code = 3;
                        $phone_number_arr['telecom'][] = $item_phonenumber;
                        break;
                    case "虚拟/联通":
                        $operator_code = 1;
                        $phone_number_arr['unicom'][] = $item_phonenumber;
                        break;
                    case "虚拟/移动":
                        $operator_code = 2;
                        $phone_number_arr['mobile'][] = $item_phonenumber;
                        break;
                    case "虚拟/电信":
                        $operator_code = 3;
                        $phone_number_arr['telecom'][] = $item_phonenumber;
                        break;
                    default:
                        $operator_code = 4;
                        $phone_number_arr['other'][] = $item_phonenumber;
                        break;
                }
                $phone_number_show = array_merge($phone_number_arr['unicom'],$phone_number_arr['mobile'],$phone_number_arr['telecom'],$phone_number_arr['other']);
                $tmpstr = "'". $item_phonenumber ."','". $item['message_id'] ."','". $item['message_code'] ."','". $item['content'] ."','". $item['send_time'] ."','". $operator_code ."','". $item['create_uid'] ."','". time() ."'";
                $sql .= "(".$tmpstr."),";

                $i++;
                if ($i > 50000) {
                    $sql = substr($sql,0,-1);   //去除最后的逗号
                    $db->query($sql);
                    $i = 0;
                    $sql="INSERT INTO yii2_message_detail (phonenumber,message_id,message_code,content,send_time,operator,create_uid,create_time) VALUES";
                }
            }
            $phonenumbers_json = json_encode($phone_number_arr);
            $db->update('yii2_message_list_detail')->cols(array('phonenumbers_json'=>$phonenumbers_json))->where('message_id='.$item['message_id'])->query();
            $sql = substr($sql,0,-1);   //去除最后的逗号
            $db->query($sql);
            $db->update('yii2_message_list')->cols(array('status'=>0))->where('message_id='.$item['message_id'])->query();
        }

        echo 'done!!';
    });  
};  
  
// 运行worker  
Worker::runAll();
