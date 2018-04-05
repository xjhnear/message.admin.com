<?php

namespace backend\controllers;

use Yii;
use backend\models\Message;
use backend\models\MessageListDetail;
use backend\models\search\MessageSearch;
use backend\models\MessageDetail;
use backend\models\search\MessageDetailSearch;
use backend\models\MessageCall;
use backend\models\search\MessageCallSearch;
use backend\models\Admin;
use backend\models\AccountDetail;
use common\helpers\ArrayHelper;
use common\helpers\FuncHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * 订单控制器
 * @author longfei <phphome@qq.com>
 */
class MessageController extends BaseController
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
        /* 添加当前位置到cookie供后续跳转调用 */
        $this->setForward();//phpinfo();

        $params = Yii::$app->request->getQueryParams();

        $searchModel = new MessageSearch();
        $dataProvider = $searchModel->search($params); //var_dump($dataProvider->query->all());exit();

        /* 导出excel */
        if (isset($params['action']) && $params['action'] == 'export') {
            $this->export($dataProvider->query->all());
            return false;
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionDetail()
    {
        /* 添加当前位置到cookie供后续跳转调用 */
        $this->setForward();//phpinfo();

        $params = Yii::$app->request->getQueryParams();

        $searchModel = new MessageDetailSearch();
        $count_arr = array();
        $count_arr['wait'] = $searchModel->getWaitCount($params['pid']);
        $count_arr['success'] = $searchModel->getSuccessCount($params['pid']);
        $count_arr['fail'] = $searchModel->getfailCount($params['pid']);
        $dataProvider = $searchModel->search($params); //var_dump($dataProvider->query->all());exit();

        /* 导出excel */
        if (isset($params['action']) && $params['action'] == 'export') {
            $this->export($dataProvider->query->all());
            return false;
        }

        return $this->render('detail', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'dataCount' => $count_arr,
        ]);
    }

    public function actionCall()
    {
        /* 添加当前位置到cookie供后续跳转调用 */
        $this->setForward();//phpinfo();

        $params = Yii::$app->request->getQueryParams();

        $searchModel = new MessageCallSearch();
        $dataProvider = $searchModel->search($params); //var_dump($dataProvider->query->all());exit();

        /* 导出excel */
        if (isset($params['action']) && $params['action'] == 'export') {
            $this->export($dataProvider->query->all());
            return false;
        }

        return $this->render('call', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * ---------------------------------------
     * 添加
     * ---------------------------------------
     */
    public function actionAdd()
    {
        $model = $this->findModel(0);
        $model_ld = new MessageListDetail();
        $balance = Yii::$app->user->identity->balance;
        $coefficient = Yii::$app->user->identity->coefficient;
        $rest = $balance;
        if (Yii::$app->request->isPost) {

            $data = Yii::$app->request->post('Message');
            $data_ld = array();
            $data['message_code'] = 'M'.time();
            if ($data['send_time'] && $data['send_time']<>"") {
                $data['send_time'] = strtotime($data['send_time']);
            } else {
                $data['send_time'] = time();
            }
            $content = array();
            $message_count = $data['message_count'];unset($data['message_count']);
            $power = 1;
            if ($message_count > 130) {
                $power = 3;
            } elseif ($message_count > 70) {
                $power = 2;
            } else {
                $power = 1;
            }
            $content['unicom'] = $data['content'];
            $content['mobile'] = $data['content1'];unset($data['content1']);
            $content['telecom'] = $data['content2'];unset($data['content2']);
            $data['content'] = $content['unicom'];
            $data_ld['content_json'] = json_encode($content);
            $is_upload = $data['upload'];unset($data['upload']);
            if ($is_upload == 1) {
                $phonenumbers = $data['phonenumbers_json'];
                $phonenumbers_arr = json_decode($phonenumbers, true);
                $phone_number_show = array_merge($phonenumbers_arr['unicom'],$phonenumbers_arr['mobile'],$phonenumbers_arr['telecom'],$phonenumbers_arr['other']);
                $data_ld['phonenumbers'] = implode(',',$phone_number_show);
                $data_ld['phonenumbers_json'] = '';
            } else {
                $phonenumbers = $data['phonenumbers'];
                if (strpos($phonenumbers,",\r\n")) {
                    $phonenumbers = explode(",\r\n",$phonenumbers);
                } elseif (strpos($phonenumbers,"\r\n")) {
                    $phonenumbers = explode("\r\n",$phonenumbers);
                } else {
                    $phonenumbers = explode(",",$phonenumbers);
                }
                $phone_number_arr = $phone_number_show = array();
                $phone_number_arr['unicom'] = $phone_number_arr['mobile'] = $phone_number_arr['telecom'] = $phone_number_arr['other'] = array();
                foreach ($phonenumbers as $item_phonenumber) {
                    if(self::validateMobile($item_phonenumber)!==true) {
                        continue;
                    }
                    $phone_number_7 =  substr($item_phonenumber,0,7);
                    $redis = Yii::$app->redis;
                    if ($redis->get("isp_".$phone_number_7)) {
                        $operator = $redis->get("isp_".$phone_number_7);
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
                $data_ld['phonenumbers'] = implode(',',$phone_number_show);
                $data_ld['phonenumbers_json'] = '';
//                $data['phonenumbers_json'] = json_encode($phone_number_arr);
                $phonenumbers_arr = $phone_number_arr;
            }
            $data['count'] = count($phone_number_show);
            $cost = $data['count'] * $power;
            if ($cost > $rest) {
                $this->error('您目前的余额只能发送'.$rest.'个号码');
            }
            $data['create_uid'] = Yii::$app->user->identity->uid;
            $data['create_name'] = Yii::$app->user->identity->username;
            /* 格式化extend值，为空或数组序列化 */
            if (isset($data['extend'])) {
                $tmp = FuncHelper::parse_field_attr($data['extend']);
                if (is_array($tmp)) {
                    $data['extend'] = serialize($tmp);
                } else {
                    $data['extend'] = '';
                }
            }
            if (isset($data_ld['extend'])) {
                $tmp = FuncHelper::parse_field_attr($data_ld['extend']);
                if (is_array($tmp)) {
                    $data_ld['extend'] = serialize($tmp);
                } else {
                    $data_ld['extend'] = '';
                }
            }
            /* 表单数据加载、验证、数据库操作 */
            if ($r = $this->saveRow($model, $data)) {
                $data_ld['message_id'] = $r->message_id;
                $this->saveRow($model_ld, $data_ld);
                $data_u = array();
                $model_a =  Admin::findOne(Yii::$app->user->identity->uid);
                $data_u['balance'] = $model_a['balance'] - $cost;
                Yii::$app->user->identity->balance = $data_u['balance'];
                $this->saveRow($model_a, $data_u);
                $model_ad = new AccountDetail();
                $attributes = array();
                $attributes['uid'] = Yii::$app->user->identity->uid;
                $attributes['change_count'] = $cost;
                $attributes['change_type'] = 2;
                $attributes['balance'] = $data_u['balance'];
                $attributes['remark'] = '消耗';
                $attributes['op_uid'] = Yii::$app->user->identity->uid;
                $this->saveRow($model_ad, $attributes);

//                $model_d = new MessageDetail();
//                foreach($phonenumbers_arr['unicom'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 1;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['mobile'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 2;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['telecom'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 3;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['other'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 4;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }

                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        $data['balance'] = $balance;
        $data['coefficient'] = $coefficient;
        $data['rest'] = $rest;
        /* 获取模型默认数据 */
        $model->loadDefaultValues();
        /* 渲染模板 */
        return $this->render('edit', [
            'model' => $model,
            'model_ld' => $model_ld,
            'data' => $data,
        ]);
    }

    /**
     * ---------------------------------------
     * 编辑
     * ---------------------------------------
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', 0);
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $data = Yii::$app->request->post('Message');//var_dump($data);exit();
            $data['send_time'] = strtotime($data['send_time']);
            /* 格式化extend值，为空或数组序列化 */
            if (isset($data['extend'])) {
                $tmp = FuncHelper::parse_field_attr($data['extend']);
                if (is_array($tmp)) {
                    $data['extend'] = serialize($tmp);
                } else {
                    $data['extend'] = '';
                }
            }
            /* 表单数据加载、验证、数据库操作 */
            if ($this->saveRow($model, $data)) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        $model->send_time = date('Y-m-d H:i', $model->send_time);
        /* 渲染模板 */
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * ---------------------------------------
     * 删除或批量删除
     * ---------------------------------------
     */
    public function actionDelete()
    {
        $model = $this->findModel(0);
        if ($this->delRow($model, 'message_id')) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * ---------------------------------------
     * 失败重发
     * ---------------------------------------
     */
    public function actionRetry()
    {
        $id = Yii::$app->request->get('pid', 0);
        $model_o = $this->findModel($id);

        $balance = Yii::$app->user->identity->balance;
        $coefficient = Yii::$app->user->identity->coefficient;
        $rest = $balance;
        if ($model_o) {
            $model = $this->findModel(0);
            $model_ld = new MessageListDetail();
            $data = $data_ld = array();
            $data['retry_pid'] = $id;
            $data['message_code'] = 'M'.time();
            $data['send_time'] = $model_o->send_time;
            $message_count = mb_strlen($model_o->content);
            $power = 1;
            if ($message_count > 130) {
                $power = 3;
            } elseif ($message_count > 70) {
                $power = 2;
            } else {
                $power = 1;
            }
            $data['content'] = $model_o->content;
            $data_ld['content_json'] = $model_o->content_json;

            $db = Yii::$app->db;
            $sql = "SELECT phonenumber FROM yii2_message_detail WHERE message_id=".$id." AND (`status`=2 OR `status`=4)";
            $command = $db->createCommand($sql);
            $number_model = $command->queryAll();
            $phonenumbers = array_column($number_model, 'phonenumber');
            $phone_number_arr = $phone_number_show = array();
            $phone_number_arr['unicom'] = $phone_number_arr['mobile'] = $phone_number_arr['telecom'] = $phone_number_arr['other'] = array();
            foreach ($phonenumbers as $item_phonenumber) {
                if(self::validateMobile($item_phonenumber)!==true) {
                    continue;
                }
                $phone_number_7 =  substr($item_phonenumber,0,7);
                $redis = Yii::$app->redis;
                if ($redis->get("isp_".$phone_number_7)) {
                    $operator = $redis->get("isp_".$phone_number_7);
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
            $data_ld['phonenumbers'] = implode(',',$phone_number_show);
            $data_ld['phonenumbers_json'] = json_encode($phone_number_arr);
            $phonenumbers_arr = $phone_number_arr;
            $data['count'] = count($phone_number_show);
            $cost = $data['count'] * $power;
            if ($data['count'] <= 0) {
                $this->error('没有需要重发的号码');
            }
            if ($cost > $rest) {
                $this->error('您目前的余额只能发送'.$rest.'个号码');
            }
            $data['create_uid'] = Yii::$app->user->identity->uid;
            $data['create_name'] = Yii::$app->user->identity->username;
            /* 格式化extend值，为空或数组序列化 */
            if (isset($data['extend'])) {
                $tmp = FuncHelper::parse_field_attr($data['extend']);
                if (is_array($tmp)) {
                    $data['extend'] = serialize($tmp);
                } else {
                    $data['extend'] = '';
                }
            }
            if (isset($data_ld['extend'])) {
                $tmp = FuncHelper::parse_field_attr($data_ld['extend']);
                if (is_array($tmp)) {
                    $data_ld['extend'] = serialize($tmp);
                } else {
                    $data_ld['extend'] = '';
                }
            }
            /* 表单数据加载、验证、数据库操作 */
            if ($r = $this->saveRow($model, $data)) {
                $data_ld['message_id'] = $r->message_id;
                $this->saveRow($model_ld, $data_ld);
                $data_u = array();
                $model_a =  Admin::findOne(Yii::$app->user->identity->uid);
                $data_u['balance'] = $model_a['balance'] - $cost;
                Yii::$app->user->identity->balance = $data_u['balance'];
                $this->saveRow($model_a, $data_u);
                $model_ad = new AccountDetail();
                $attributes = array();
                $attributes['uid'] = Yii::$app->user->identity->uid;
                $attributes['change_count'] = $cost;
                $attributes['change_type'] = 2;
                $attributes['balance'] = $data_u['balance'];
                $attributes['remark'] = '消耗';
                $attributes['op_uid'] = Yii::$app->user->identity->uid;
                $this->saveRow($model_ad, $attributes);

                $db = Yii::$app->db;
                $sql="update yii2_message_list set is_retry = 1 where message_id=".$id;
                $command = $db->createCommand($sql);
                $r = $command->execute();

//                $model_d = new MessageDetail();
//                foreach($phonenumbers_arr['unicom'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 1;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['mobile'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 2;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['telecom'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 3;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }
//                foreach($phonenumbers_arr['other'] as $phonenumber)
//                {
//                    $attributes = array();
//                    $attributes['phonenumber'] = $phonenumber;
//                    $attributes['message_id'] = $r->message_id;
//                    $attributes['message_code'] = $data['message_code'];
//                    $attributes['content'] = $data['content'];
//                    $attributes['send_time'] = $data['send_time'];
//                    $attributes['operator'] = 4;
//                    $attributes['create_uid'] = Yii::$app->user->identity->uid;
//                    $_model_d = clone $model_d;
//                    $this->saveRow($_model_d, $attributes);
//                }

                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }

        $this->error('操作错误');
    }

    /**
     * ---------------------------------------
     * 导出excel
     * ---------------------------------------
     */
    public function export($data)
    {
        /*$data = Message::find()->where(['pay_status' => 1])
            ->andWhere(['status'=>1])
            ->orderBy('message_id DESC')
            ->asArray()->all();*/
        $arr = [];
        $title = ['ID', '批次号', '发送时间', '状态', '创建时间', '身份证', '商品类型', '套餐ID', '商品ID', '商品名', '起租时间', '退租时间',
            '数量', '价格', '支付状态', '支付时间', '支付类型', '支付途径', '下单时间', '状态'];
        if ($data) {
            foreach ($data as $key => $value) {
                $arr[$key] = $value;
                $arr[$key]['start_time'] = date('Y-m-d H:i', $value['start_time']);
                $arr[$key]['end_time'] = date('Y-m-d H:i', $value['end_time']);
                $arr[$key]['pay_time'] = $value['pay_time'] ? date('Y-m-d H:i', $value['end_time']) : 0;
                $arr[$key]['create_time'] = $value['create_time'] ? date('Y-m-d H:i', $value['create_time']) : 0;
                $arr[$key]['pay_status'] = Yii::$app->params['pay_status'][$value['pay_status']];
                $arr[$key]['pay_type'] = Yii::$app->params['pay_type'][$value['pay_type']];
                $arr[$key]['pay_source'] = Yii::$app->params['pay_source'][$value['pay_source']];
                $arr[$key]['status'] = '订单正常';
            }
        }

        FuncHelper::exportexcel($arr, $title);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if ($id == 0) {
            return new Message();
        }
        if (($model = Message::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }


    public function actionGetAjax(){
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        ini_set("post_max_size", "100M");
        ini_set("upload_max_filesize", "100M");
        setlocale(LC_ALL, 'zh_CN');

        $returnPath = "";
        if (Yii::$app->request->isPost) {
            $path = "uploads/".date("Ymd");
            if (!file_exists($path)) {
                mkdir($path, 0777);
                chmod($path, 0777);
            }

            $tmp = UploadedFile::getInstanceByName('fileUpload');
            if ($tmp) {
                $ext = $tmp->getExtension();
                $name = $tmp->getBaseName();
                if(!in_array($ext,array('csv','txt'))) return json_encode(array('state'=>0,'msg'=>'上传文件格式错误'));
                $patch = $path . '/' . date("YmdHis") . '_';
                $tmp->saveAs($patch . $name . '.' . $ext);
                $returnPath .= $patch . $name . '.' . $ext;

                $handle = fopen($returnPath, 'r');
                $result = self::input_csv($handle); //解析csv
                $len_result = count($result);
                if($len_result==0){
                    return json_encode(array('state'=>0,'msg'=>'没有任何数据'));
                }
                $phone_number_arr = $phone_number_show = array();
                $unicom = $mobile = $telecom = 0;
                $phone_number_arr['unicom'] = $phone_number_arr['mobile'] = $phone_number_arr['telecom'] = $phone_number_arr['other'] = array();
                for ($j = 0; $j < $len_result; $j++) { //循环获取各字段值
                    if(self::validateMobile($result[$j][0])!==true) {
                        continue;
                    }
                    $phone_number = isset($result[$j][0])?self::characet($result[$j][0]):''; //中文转码
                    $phone_number_7 =  substr($phone_number,0,7);
                    $redis = Yii::$app->redis;
                    if ($redis->get("isp_".$phone_number_7)) {
                        $operator = $redis->get("isp_".$phone_number_7);
                    } else {
                        $operator = '';
                    }
                    switch ($operator) {
                        case "联通":
                            $unicom++;
                            $phone_number_arr['unicom'][] = $phone_number;
                            break;
                        case "移动":
                            $mobile++;
                            $phone_number_arr['mobile'][] = $phone_number;
                            break;
                        case "电信":
                            $telecom++;
                            $phone_number_arr['telecom'][] = $phone_number;
                            break;
                        case "虚拟/联通":
                            $unicom++;
                            $phone_number_arr['unicom'][] = $phone_number;
                            break;
                        case "虚拟/移动":
                            $mobile++;
                            $phone_number_arr['mobile'][] = $phone_number;
                            break;
                        case "虚拟/电信":
                            $telecom++;
                            $phone_number_arr['telecom'][] = $phone_number;
                            break;
                        default:
                            $phone_number_arr['other'][] = $phone_number;
                            break;
                    }
                    $phone_number_show = array_merge($phone_number_arr['unicom'],$phone_number_arr['mobile'],$phone_number_arr['telecom'],$phone_number_arr['other']);
                }
                fclose($handle); //关闭指针
                return json_encode(array("state"=>1,'msg'=>'添加成功','phone'=>implode(',',$phone_number_show),'phone_json'=>json_encode($phone_number_arr),'phone_count'=>array('all'=>count($phone_number_show),'unicom'=>$unicom,'mobile'=>$mobile,'telecom'=>$telecom)));

            } else {
                return json_encode(array('state'=>0,'msg'=>'文件不存在'));
            }
        }else{
            return json_encode(array('state'=>0,'msg'=>'添加失败'));
        }
    }

    private function input_csv($handle) {
        $out = array ();
        $n = 0;
        while ($data = fgetcsv($handle, 10000)) {
            $num = count($data);
            if ($num == 1) {
                //$data[0] = trim($data[0], "\xEF\xBB\xBF");
                if (strpos($data[0],"\t") > 0) {
                    $data[0] = preg_replace("/\t/",",",$data[0]);
                    $data = explode(',',$data[0]);
                    $num = count($data);
                }
            }
            for ($i = 0; $i < $num; $i++) {
                $out[$n][$i] = $data[$i];
            }
            $n++;
        }
        return $out;
    }

    private function validateMobile($mobile)
    {
        if(!$mobile) return false;
        if(preg_match("/^1[0-9]{2}[0-9]{8}$/",$mobile)){
            return true;
        }
        return false;
    }

    private function characet($data){
        if( !empty($data) ){
            $fileType = mb_detect_encoding($data , array('UTF-8','GBK','LATIN1','BIG5')) ;
            if( $fileType != 'UTF-8'){
                $data = mb_convert_encoding($data ,'utf-8' , $fileType.'//IGNORE');
            }
        }
        return $data;
    }

    public function actionUpdateRedis()
    {
        set_time_limit(0);
        ini_set("memory_limit", "1024M");
        ini_set("post_max_size", "100M");
        ini_set("upload_max_filesize", "100M");
        setlocale(LC_ALL, 'zh_CN');
        $db = Yii::$app->db;
        $sql = "SELECT phone,province,isp FROM yii2_phone_model";
        $command = $db->createCommand($sql);
        $number_model = $command->queryAll();
        $redis = Yii::$app->redis;
        foreach ($number_model as $number_model_item) {
            $redis->set("province_".$number_model_item['phone'],$number_model_item['province']);
            $redis->set("isp_".$number_model_item['phone'],$number_model_item['isp']);
        }
        print_r("done!!!");exit;
    }

}
