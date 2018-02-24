<?php

namespace backend\controllers;

use Yii;
use backend\models\search\MessageSendSearch;

/**
 * 后台首页控制器
 * @author longfei <phphome@qq.com>
 */
class HomeController extends BaseController
{
    public function actionIndex()
    {
        $dataProvider['balance'] = Yii::$app->user->identity->balance;

        $message_send = new MessageSendSearch();
        if (Yii::$app->user->identity->role == 1) {
            $uid = Yii::$app->user->identity->uid;
        } else {
            $uid = null;
        }
        $dataProvider['subtotal_today'] = $message_send->getTodayCount($uid);
        $dataProvider['subtotal_today_success'] = $message_send->getTodaySuccessCount($uid);
        $dataProvider['subtotal_thismonth'] = $message_send->getThisMonthCount($uid);
        $dataProvider['subtotal_thismonth_success'] = $message_send->getThisMonthSuccessCount($uid);
        $dataProvider['subtotal_today_per'] = 0;
        $dataProvider['subtotal_thismonth_per'] = 0;
        if ($dataProvider['subtotal_today'] > 0) {
            $dataProvider['subtotal_today_per'] = ceil(($dataProvider['subtotal_today_success']/$dataProvider['subtotal_today'])*100);
        }
        if ($dataProvider['subtotal_thismonth'] > 0) {
            $dataProvider['subtotal_thismonth_per'] = ceil(($dataProvider['subtotal_thismonth_success']/$dataProvider['subtotal_thismonth'])*100);
        }
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
