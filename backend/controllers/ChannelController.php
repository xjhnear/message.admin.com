<?php

namespace backend\controllers;

use Yii;
use backend\models\Channel;
use backend\models\search\ChannelSearch;

/**
 * 后台用户控制器
 * @author longfei <phphome@qq.com>
 */
class ChannelController extends BaseController
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

        $searchModel = new ChannelSearch();
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

        $model = new Channel();

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post('Channel');
            $model->setAttributes($data);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }
        $model->type = 1;
        $model->status = 1;
        $model->operator = 1;
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
        $model = Channel::findOne($uid);

        if (Yii::$app->request->isPost) {
            /* 表单验证 */
            $data = Yii::$app->request->post('Channel');
            $model->setAttributes($data);
            /* 保存用户数据到数据库 */
            if ($model->save()) {
                $this->success('操作成功', $this->getForward());
            } else {
                $this->error('操作错误');
            }
        }

        return $this->render('edit', [
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
        $model = Channel::findOne($uid);
        $data['is_del'] = 1;
        $model->setAttributes($data);
        if ($model->save()) {
            $this->success('删除成功', $this->getForward());
        } else {
            $this->error('删除失败！');
        }
    }

}
