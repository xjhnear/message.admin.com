<?php


use yii\helpers\Html;
use yii\helpers\Url;
use common\core\ActiveForm;
use common\helpers\ArrayHelper;
//use backend\models\Train;
//use backend\models\Shop;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form ActiveForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '添加商户';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 内容信息</span>
        </div>
    </div>
    <div class="portlet-body form">
        <!-- 这里注意了，不能使用pjax，因为第三方库中有行内js，会导致js加载失败 -->
        <!-- BEGIN FORM-->
        <?php $form = ActiveForm::begin([
            'options'=>[
                'class'=>"form-aaa"
            ]
        ]); ?>

        <?= $form->field($model, 'username')->iconTextInput([
            'class'=>'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-user',
            'placeholder' => 'username'
        ])->label('用户名') ?>

        <?= $form->field($model, 'password')->iconTextInput([
            'class'=>'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-lock',
            'placeholder' => '修改时密码不变请留空'
        ])->label('密码') ?>

        <?= $form->field($model, 'email')->iconTextInput([
            'class'=>'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'icon-envelope',
            'placeholder' => 'Email Address'
        ])->label('邮箱') ?>

        <?= $form->field($model, 'mobile')->iconTextInput([
            'class'=>'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'fa fa-mobile',
            'placeholder' => 'Mobile'
        ])->label('电话') ?>

        <?= $form->field($model, 'coefficient')->iconTextInput([
            'class'=>'form-control c-md-3',
            'iconPos' => 'left',
            'iconClass' => 'fa fa-diamond',
            'placeholder' => '单位:元/条'
        ])->label('单价') ?>

        <?= $form->field($model, 'status')->radioList(['1'=>'正常','0'=>'隐藏'])->label('用户状态') ?>
        
        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 提交', ['class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
            <?= Html::button('取消', ['class' => 'btn','onclick'=>'JavaScript:history.go(-1)']) ?>
        </div>
        
        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>
</div>

<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>

$(function() {
    /* 子导航高亮 */
    highlight_subnav('customer/index');
});

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
