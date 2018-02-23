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
//		$all_tables_1 = RedisDb::instance('redis')->get('isp_1391743');
//		print_r($all_tables_1);exit;

		$now_time = time();
		$now_date = date('Y-m-d');
		$db= new MysqlConnection('127.0.0.1', '3306', 'root', 'near','message_www');
        $all_tables=$db->select(array('message_sid','message_id','task_id','operator','channel_id'))->from('yii2_message_send')->where('status = 0')->query();
        $all_tables_arr = array();
        foreach ($all_tables as $item) {
            $all_tables_arr[$item['task_id']] = $item;
        }

        $url = 'http://139.196.58.248:5577/statusApi.aspx';
        $userid = '8710';
        $account = '借鸿移动贷款';
        $password = 'a123456';
        $params = array(
            'userid'=>$userid,
            'account'=>$account,
            'password'=>$password,
            'action'=>'query'
        );
        $o = "";
        foreach ( $params as $k => $v )
        {
            $o.= "$k=" . urlencode($v). "&" ;
        }
        $post_data = substr($o,0,-1);
        $postUrl = $url;
        $curlPost = $post_data;
        $ch = curl_init();//初始化curl
        curl_setopt($ch, CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data = curl_exec($ch);//运行curl
        curl_close($ch);
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring),true);
        foreach ($val['statusbox'] as $item) {
            $all_tables=$db->update('yii2_message_detail')->cols(array('status'=>$item['status'],'return_time'=>$now_time))->where('phonenumber='.$item['mobile'])->query();
		}
    });  
};  
  
// 运行worker  
Worker::runAll();
