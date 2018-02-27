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
        <title>登录 | 短信审核平台</title>
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
                    'enableClientValidation' => false,
                    'class'=>"login-form",
                    'action'=>\yii\helpers\Url::toRoute('login/login'),
                    'method'=>"post"
                ]
            ]); ?>
                <h3 class="form-title font-green">短信审核平台</h3>
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
                <div class="form-group">
                    <input class="form-control" style="width: 226px; display: inline-block;" placeholder="请输入验证码" name="info[verifyCode]" type="text">
                    <!--验证码输出，调用Captcha类下的widget方法，需传入必要的配置信息，name属性必须要传入，captchaAction属性指定是哪个控制器下的哪个方法，site/captcha就是上文我们在SiteController的actions中定义的验证码
                    方法（其实在SiteCOntroller中的actions定义的，可以不添加该项，因为默认是SiteController，如果是在其他控制器中定义的，则必须添加该项）。imageOptions可以制定一些html标签属性属性，template指定模板，
                    这里只输出img标签，故只用了{image}-->
                    <?=Captcha::widget(['name'=>'captcha-img','captchaAction'=>'login/captcha','imageOptions'=>['id'=>'captcha-img', 'title'=>'换一个', 'style'=>'cursor:pointer;'],'template'=>'{image}']);?>
                </div>
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

    <?php $this->endBody() ?>
    </body>

</html>
<?php $this->endPage() ?>