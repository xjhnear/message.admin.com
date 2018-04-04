<?php

namespace backend\controllers;

use Yii;
use backend\models\search\MessageSendSearch;
use backend\models\search\MessageDetailSearch;

/**
 * 后台首页控制器
 * @author longfei <phphome@qq.com>
 */
class HomeController extends BaseController
{
    public function actionIndex()
    {
        $dataProvider['balance'] = Yii::$app->user->identity->balance;
        $dataProvider['coefficient'] = Yii::$app->user->identity->coefficient;

        $message_send = new MessageSendSearch();
        $message_detail = new MessageDetailSearch();
        if (Yii::$app->user->identity->role == 1) {
            $uid = Yii::$app->user->identity->uid;
        } else {
            $uid = null;
        }
        $dataProvider['subtotal_today'] = $message_detail->getTodayCount($uid);
        $dataProvider['subtotal_today_success'] = $message_send->getTodaySuccessCount($uid);
        $dataProvider['subtotal_thismonth'] = $message_detail->getThisMonthCount($uid);
        $dataProvider['subtotal_thismonth_success'] = $message_send->getThisMonthSuccessCount($uid);
        $dataProvider['subtotal_today_per'] = 0;
        $dataProvider['subtotal_thismonth_per'] = 0;
        if ($dataProvider['subtotal_today'] > 0) {
            $dataProvider['subtotal_today_per'] = ceil(($dataProvider['subtotal_today_success']/$dataProvider['subtotal_today'])*100);
        }
        if ($dataProvider['subtotal_thismonth'] > 0) {
            $dataProvider['subtotal_thismonth_per'] = ceil(($dataProvider['subtotal_thismonth_success']/$dataProvider['subtotal_thismonth'])*100);
        }
        $dataProvider['pai'] = ceil(($dataProvider['subtotal_today_per'] + $dataProvider['subtotal_thismonth_per'] + 99)/3);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
