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
$this->title = '添加通道';
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

        <?=$form->field($model, 'name')->textInput(['class' => 'form-control c-md-3'])->label('通道名称')->hint('')?>

        <?=$form->field($model, 'userid')->textInput(['class' => 'form-control c-md-3'])->label('用户id')->hint('')?>

        <?=$form->field($model, 'account')->textInput(['class' => 'form-control c-md-3'])->label('账号')->hint('')?>

        <?=$form->field($model, 'password')->textInput(['class' => 'form-control c-md-3'])->label('密码')->hint('')?>

        <?= $form->field($model, 'operator')->radioList(['1'=>'联通','2'=>'移动','3'=>'电信'])->label('运营商') ?>

        <?= $form->field($model, 'status')->radioList(['1'=>'正常','0'=>'隐藏'])->label('通道状态') ?>
        
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
    highlight_subnav('channel/index');
});

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
