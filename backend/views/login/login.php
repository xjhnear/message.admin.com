<?php
use yii\captcha\Captcha;
use common\core\ActiveForm;
\backend\assets\AppAsset::register($this);
\backend\assets\LoginAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->

    <head>
        <meta charset="utf-8" />
        <title>登录 | 提都营销平台</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="" name="description" />
        <meta content="" name="author" />
        <?php $this->head() ?>
        <link rel="shortcut icon" href="favicon.ico" />
        <script language="JavaScript">
            var BaseUrl = '<?=Yii::getAlias('@web')?>';
        </script>
    </head>
    <!-- END HEAD -->

    <body class=" login">
    <?php $this->beginBody() ?>
        <!-- BEGIN LOGO -->
        <div class="logo">
        </div>
        <!-- END LOGO -->
        <!-- BEGIN LOGIN -->
        <div class="content">
            <!-- BEGIN LOGIN FORM -->
<!--            <form class="login-form" action="--><?//=\yii\helpers\Url::toRoute('login/login')?><!--" method="post">-->
            <?php $form = ActiveForm::begin([
                'options'=>[
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,     //提交时的验证
                        'validateOnChange'=>true,     //输入框值改变时的验证
                        'validateOnType'=>false,      //键入时验证
                    ),
                    'class'=>"login-form",
                    'action'=>\yii\helpers\Url::toRoute('login/login'),
                    'method'=>"post"
                ]
            ]); ?>
                <h3 class="form-title font-green">提都营销平台</h3>
                <div class="alert alert-danger display-hide">
                    <button class="close" data-close="alert"></button>
                    <span> 登录失败 </span>
                </div>
                <div class="form-group">
                    <!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
                    <label class="control-label visible-ie8 visible-ie9">用户名</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="text" autocomplete="off" placeholder="用户名" name="info[username]" />
                </div>
                <div class="form-group">
                    <label class="control-label visible-ie8 visible-ie9">密码</label>
                    <input class="form-control form-control-solid placeholder-no-fix" type="password" autocomplete="off" placeholder="密码" name="info[password]" />
                </div>
                <?= $form->field($model, 'verifyCode')->label(false)->widget(Captcha::className(), [
                    'class'=>'form-control c-md-13',
                    'template' => '<input class="form-control" style="width: 200px; display: inline-block;" placeholder="请输入验证码" name="info[verifyCode]" type="text">{image}',
                    'imageOptions'=>['id'=>'captcha-img', 'title'=>'点击换图', 'alt'=>'点击换图', 'style'=>'cursor:pointer;'],
                    'name'=>'captcha-img',
                    'captchaAction'=>'login/captcha'
                ],['class'=>'form-group']) ?>
                <div class="form-actions">
                    <label class="rememberme check mt-checkbox mt-checkbox-outline" style="padding-left:25px;">
                        <input type="checkbox" name="info[rememberMe]" value="1" checked/>记住我
                        <span></span>
                    </label>
                    <button type="submit" class="btn green pull-right" style="margin-top:-10px;">登录</button>
                </div>
                <div class="create-account"></div>
            <?php ActiveForm::end();?>
<!--            </form>-->
            <!-- END LOGIN FORM -->
        </div>
    <div class="copyright"> Copyright &copy; 2018 Wenyanhong All Rights Reserved </div>
    <?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
    <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>