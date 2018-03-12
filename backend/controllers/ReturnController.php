<?php

namespace backend\controllers;

use Yii;
use common\helpers\ArrayHelper;
use common\helpers\FuncHelper;
use yii\web\NotFoundHttpException;

/**
 * 订单控制器
 * @author longfei <phphome@qq.com>
 */
class ReturnController extends BaseController
{
    /**
     * ---------------------------------------
     * 列表页
     * ---------------------------------------
     */
    public function init(){
        $this->enableCsrfValidation = false;
    }

    public function actionIndex()
    {
        $db = Yii::$app->db;
        $start = mktime(0,0,0,date("m"),date("d")-4,date("Y"));
        $end = mktime(0,0,0,date("m"),date("d")-3,date("Y"));
        $sql="SELECT a.message_id  FROM
(SELECT DISTINCT message_id
FROM yii2_message_detail WHERE `status`=5) a
INNER JOIN
(SELECT DISTINCT message_id
FROM yii2_message_send WHERE create_time<".$end." AND `status`=0) b
ON a.message_id = b.message_id";
        $command = $db->createCommand($sql);
        $message_id = $command->queryAll();
        $model = array();
        if ($message_id) {
            foreach ($message_id[0] as $item) {
                $item_model = array();
                $sql_count="select count(*) as num,create_uid,content,message_code,send_time from yii2_message_detail where status=5 and message_id =".$item." group by content,create_uid,message_code,send_time";
                $command = $db->createCommand($sql_count);
                $item_count = $command->queryAll();
                $balance = $create_uid = 0;
                foreach ($item_count as $item_c) {
                    $message_count = mb_strlen($item_c['content']);
                    $power = 1;
                    if ($message_count > 130) {
                        $power = 3;
                    } elseif ($message_count > 70) {
                        $power = 2;
                    } else {
                        $power = 1;
                    }
                    $create_uid = $item_c['create_uid'];
                    $message_code = $item_c['message_code'];
                    $send_time = date('Y-m-d H:i:s', $item_c['send_time']);
                    $balance += $item_c['num'] * $power;
                }
                $item_model['message_id'] = $item;
                $item_model['create_uid'] = $create_uid;
                $item_model['message_code'] = $message_code;
                $item_model['send_time'] = $send_time;
                $item_model['balance'] = $balance;
                $model[] = $item_model;
            }
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Config');
            if ($data['model_json']) {
                $json_arr = json_decode($data['model_json'],true);
                foreach ($json_arr as $item_json) {
                    $sql_config='update yii2_message_detail set status=4, errmsg="超时" where status=5 and message_id ='.$item_json['message_id'];
                    $command = $db->createCommand($sql_config);
                    $r = $command->execute();
                    $sql_config='update yii2_admin set balance=balance+'.$item_json['balance'].' where uid ='.$item_json['create_uid'];
                    $command = $db->createCommand($sql_config);
                    $r = $command->execute();
                    $sql_config='select balance from yii2_admin where uid ='.$item_json['create_uid'];
                    $command = $db->createCommand($sql_config);
                    $balance_now = $command->queryOne();
                    $sql_config='INSERT INTO yii2_account_detail (uid,change_count,change_type,balance,remark,op_uid,create_time) VALUES ("'.$item_json['create_uid'].'","'.$item_json['balance'].'","1","'.$balance_now['balance'].'","返还","0","'.time().'")';
                    $command = $db->createCommand($sql_config);
                    $r = $command->execute();
                    if ($r) {
                        $this->success('操作成功');
                    } else {
                        $this->error('操作错误');
                    }
                }
            } else {
                $this->error('操作错误');
            }
        }
        return $this->render('index', [
            'model' => $model,
            'model_json' => json_encode($model),
        ]);
    }

    public function actionConfig()
    {
        $db = Yii::$app->db;
        $sql_config="select `value` from yii2_config where `name`='AUTO_TIMEOUT_API'";
        $command = $db->createCommand($sql_config);
        $item_config = $command->queryOne();
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Config');
            $sql_config="update yii2_config set `value` = ".$data['value']." where `name`='AUTO_TIMEOUT_API'";
            $command = $db->createCommand($sql_config);
            $r = $command->execute();
            /* 表单数据加载、验证、数据库操作 */
            if ($r) {
                $this->success('操作成功');
            } else {
                $this->error('操作错误');
            }
        }
        return $this->render('config', [
            'model' => $item_config,
        ]);
    }

}