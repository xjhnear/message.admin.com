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
        $model_arr = array();
        if ($message_id) {
            foreach ($message_id[0] as $item) {
                $item_arr = array();
                $sql_count="select count(*) as num,create_uid,content from yii2_message_detail where status=5 and message_id =".$item." group by content,create_uid";
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
                    $balance += $item_c['num'] * $power;
                }
                $item_arr['message_id'] = $item;
                $item_arr['create_uid'] = $create_uid;
                $item_arr['balance'] = $balance;
                $model_arr[] = $item_arr;
            }
        }
        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Return');//var_dump($data);exit();

            $sql = "UPDATE yii2_config SET `value`=".$data['value']." WHERE name='AUTO_TIMEOUT_API'";
            $command = $db->createCommand($sql);
            $r = $command->execute();

            /* 表单数据加载、验证、数据库操作 */
            if ($r) {
                $this->success('操作成功');
            } else {
                $this->error('操作错误');
            }
        }
        /* 渲染模板 */
        return $this->render('index', [
            'model' => $model_arr,
        ]);
    }


    public function actionConfig()
    {
        $db = Yii::$app->db;
        $sql = "select `value` from yii2_config where `name`='AUTO_TIMEOUT_API'";
        $command = $db->createCommand($sql);
        $item_config = $command->queryOne();

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Return');//var_dump($data);exit();

            $sql = "UPDATE yii2_config SET `value`=".$data['value']." WHERE name='AUTO_TIMEOUT_API'";
            $command = $db->createCommand($sql);
            $r = $command->execute();

            /* 表单数据加载、验证、数据库操作 */
            if ($r) {
                $this->success('操作成功');
            } else {
                $this->error('操作错误');
            }
        }
        /* 渲染模板 */
        return $this->render('config', [
            'model' => $item_config,
        ]);
    }


}
