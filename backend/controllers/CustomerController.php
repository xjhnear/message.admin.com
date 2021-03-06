<?php

namespace backend\controllers;

use Yii;
use backend\models\Admin;
use backend\models\search\CustomerSearch;
use backend\models\AccountDetail;
use backend\models\AuthAssignment;

/**
 * 后台用户控制器
 * @author longfei <phphome@qq.com>
 */
class CustomerController extends BaseController
{
    /**
     * ---------------------------------------
     * 构造方法
     * ---------------------------------------
     */
    public function init()
    {
        parent::init();
    }

    /**
     * ---------------------------------------
     * 用户列表
     * ---------------------------------------
     */
    public function actionIndex()
    {
        /* 添加当前位置到cookie供后续操作调用 */
        $this->setForward();

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * ---------------------------------------
     * 添加
     * ---------------------------------------
     */
    public function actionAdd()
    {

        $model = new Admin();

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post('Admin');
            $data['reg_time'] = time();
            $data['reg_ip'] = ip2long(Yii::$app->request->getUserIP());
            $data['last_login_time'] = 0;
            $data['last_login_ip'] = ip2long('127.0.0.1');
            $data['update_time'] = 0;
            $data['role'] = 1;
            /* 表单数据加载和验证，具体验证规则在模型rule中配置 */
            /* 密码单独验证，否则setPassword后密码肯定符合rule */
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $this->error('密码为空或小于6字符');
            }
            $model->setAttributes($data);
            $model->generateAuthKey();
            $model->setPassword($data['password']);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                $model_au = new AuthAssignment();
                $attributes = array();
                $attributes['user_id'] = $model->getId();
                $attributes['item_name'] = '商户';
                $this->saveRow($model_au, $attributes);
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        $model->status = 1;
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    /**
     * ---------------------------------------
     * 编辑
     * ---------------------------------------
     */
    public function actionEdit($uid)
    {
        $model = Admin::findOne($uid);

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post('Admin');
            $data['update_time'] = time();
            /* 如果设置密码则重置密码，否则不修改密码 */
            if (!empty($data['password'])) {
                $model->generateAuthKey();
                $model->setPassword($data['password']);
            }
            unset($data['password']);

            $model->setAttributes($data);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }

        $model->password = '';
        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionRecharge($uid)
    {
        $model = Admin::findOne($uid);

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post('Recharge');
            $balance = $model->balance;
            $type = $data['type'];unset($data['type']);
            $userremark = $data['userremark'];unset($data['userremark']);
            $change_count = $data['count'];
            if ($type == 2) {
                $balance -= $change_count;
            } else {
                $balance += $change_count;
            }
            $data['balance'] = $balance;
            unset($data['count']);

            $model->setAttributes($data);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                $model_ad = new AccountDetail();
                $attributes = array();
                $attributes['uid'] = $uid;
                $attributes['change_count'] = $change_count;
                $attributes['balance'] = $balance;
                Yii::$app->user->identity->balance = $balance;
                if ($type == 1) {
                    $attributes['remark'] = '充值';
                    $attributes['change_type'] = 1;
                } elseif ($type == 2) {
                    $attributes['remark'] = '扣除';
                    $attributes['change_type'] = 2;
                } else {
                    $attributes['remark'] = '返还';
                    $attributes['change_type'] = 1;
                }
                $attributes['userremark'] = $userremark;
                $attributes['op_uid'] = Yii::$app->user->identity->uid;
                $this->saveRow($model_ad, $attributes);
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }

        return $this->render('recharge', [
            'model' => $model,
        ]);
    }

    /**
     * ---------------------------------------
     * 删除
     * ---------------------------------------
     */
    public function actionDelete()
    {
        $uid = Yii::$app->request->param('id', 0);
        $model = Admin::findOne($uid);
        $data['is_del'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

}
