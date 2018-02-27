<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use yii\helpers\Url;

/**
 * 后台登录控制器
 * @author longfei <phphome@qq.com>
 */
class LoginController extends Controller
{

    public $layout = false;

    public $enableCsrfValidation = false;

    public $defaultAction = 'login';

    /**
     * ---------------------------------------
     * 行为控制
     * ---------------------------------------
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'logout', 'captcha'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            //验证码action
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'backColor'=>0x000000,//背景颜色
                'maxLength' => 5, //最大显示个数
                'minLength' => 4,//最少显示个数
                'padding' => 3,//间距
                'height'=>34,//高度
                'width' => 90,  //宽度
                'foreColor'=>0xffffff,     //字体颜色
                'offset'=>4        //设置字符偏移量 有效果
            ],
        ];
    }

    /**
     * ---------------------------------------
     * 登录页
     * ---------------------------------------
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome(); // 默认 index/index
        }

        $model = new LoginForm();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), 'info') && $model->login()) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * ---------------------------------------
     * 注销页
     * ---------------------------------------
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(Url::toRoute('/login/login'));
    }
}
