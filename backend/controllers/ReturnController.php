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
        $end = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
        $sql="SELECT DISTINCT message_id
FROM yii2_message_detail WHERE `status`=4 AND is_return = 0 AND create_time<".$end;
        $command = $db->createCommand($sql);
        $message_id = $command->queryAll();
        $model = array();
        if ($message_id) {
            foreach ($message_id as $item) {
                $item_model = array();
                $sql_count="select count(*) as num,create_uid,content,message_code,send_time from yii2_message_detail where status=4 AND is_return = 0 and message_id =".$item['message_id']." group by content,create_uid,message_code,send_time";
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
                $item_model['message_id'] = $item['message_id'];
                $item_model['create_uid'] = $create_uid;
                $sql_config='select username from yii2_admin where uid ='.$create_uid;
                $command = $db->createCommand($sql_config);
                $balance_now = $command->queryOne();
                $item_model['create_name'] = $balance_now['username'];
                $item_model['message_code'] = $message_code;
                $item_model['send_time'] = $send_time;
                $item_model['balance'] = $balance;
                $model[] = $item_model;
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

    public function actionOk()
    {
        $id = Yii::$app->request->get('id', 0);
        $db = Yii::$app->db;
        $sql_count="select count(*) as num,create_uid,content,message_code,send_time from yii2_message_detail where status=4 AND is_return = 0 and message_id =".$id." group by content,create_uid,message_code,send_time";
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

        $sql_config='update yii2_message_detail set is_return=1 where status=4 AND is_return = 0 and message_id ='.$id;
        $command = $db->createCommand($sql_config);
        $r = $command->execute();
        $sql_config='update yii2_admin set balance=balance+'.$balance.' where uid ='.$create_uid;
        $command = $db->createCommand($sql_config);
        $r = $command->execute();
        $sql_config='select balance from yii2_admin where uid ='.$create_uid;
        $command = $db->createCommand($sql_config);
        $balance_now = $command->queryOne();
        $sql_config='INSERT INTO yii2_account_detail (uid,change_count,change_type,balance,remark,op_uid,create_time) VALUES ("'.$create_uid.'","'.$balance.'","1","'.$balance_now['balance'].'","返还","0","'.time().'")';
        $command = $db->createCommand($sql_config);
        $r = $command->execute();

        /* 表单数据加载、验证、数据库操作 */
        if ($r) {
            $this->success('操作成功', $this->getForward());
        } else {
            $this->error('操作错误');
        }
    }

    public function actionReject()
    {
        $id = Yii::$app->request->get('id', 0);
        $db = Yii::$app->db;
        $sql_config='update yii2_message_detail set is_return=1 where status=4 AND is_return = 0 and message_id ='.$id;
        $command = $db->createCommand($sql_config);
        $r = $command->execute();
        /* 表单数据加载、验证、数据库操作 */
        if ($r) {
            $this->success('操作成功', $this->getForward());
        } else {
            $this->error('操作错误');
        }
    }

    public function actionOkall()
    {
        $ids = Yii::$app->request->get('ids', 0);
        if ($ids == 0) $this->error('操作错误');
        $ids = explode(',',$ids);
        $db = Yii::$app->db;
        foreach ($ids as $id) {
            $sql_count="select count(*) as num,create_uid,content,message_code,send_time from yii2_message_detail where status=4 AND is_return = 0 and message_id =".$id." group by content,create_uid,message_code,send_time";
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

            $sql_config='update yii2_message_detail set is_return=1 where status=4 AND is_return = 0 and message_id ='.$id;
            $command = $db->createCommand($sql_config);
            $r = $command->execute();
            $sql_config='update yii2_admin set balance=balance+'.$balance.' where uid ='.$create_uid;
            $command = $db->createCommand($sql_config);
            $r = $command->execute();
            $sql_config='select balance from yii2_admin where uid ='.$create_uid;
            $command = $db->createCommand($sql_config);
            $balance_now = $command->queryOne();
            $sql_config='INSERT INTO yii2_account_detail (uid,change_count,change_type,balance,remark,op_uid,create_time) VALUES ("'.$create_uid.'","'.$balance.'","1","'.$balance_now['balance'].'","返还","0","'.time().'")';
            $command = $db->createCommand($sql_config);
            $r = $command->execute();
        }
        /* 表单数据加载、验证、数据库操作 */
        if ($r) {
            $this->success('操作成功', $this->getForward());
        } else {
            $this->error('操作错误');
        }
    }

    public function actionRejectall()
    {
        $ids = Yii::$app->request->get('ids', 0);
        if ($ids == 0) $this->error('操作错误');
        $ids = explode(',',$ids);
        $db = Yii::$app->db;
        foreach ($ids as $id) {
            $sql_config='update yii2_message_detail set is_return=1 where status=4 AND is_return = 0 and message_id ='.$id;
            $command = $db->createCommand($sql_config);
            $r = $command->execute();
        }
        /* 表单数据加载、验证、数据库操作 */
        if ($r) {
            $this->success('操作成功', $this->getForward());
        } else {
            $this->error('操作错误');
        }
    }

}
