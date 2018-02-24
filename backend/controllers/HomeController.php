<?php

namespace backend\controllers;

use Yii;
use backend\models\MessageSend;

/**
 * 后台首页控制器
 * @author longfei <phphome@qq.com>
 */
class HomeController extends BaseController
{
    public function actionIndex()
    {
        $dataProvider['balance'] = Yii::$app->user->identity->balance;

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
