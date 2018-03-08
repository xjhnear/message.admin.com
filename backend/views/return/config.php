<?php


use yii\helpers\Html;
use yii\helpers\Url;
use common\core\ActiveForm;
use common\helpers\ArrayHelper;
use backend\assets\AppAsset;
//use backend\models\Train;
//use backend\models\Shop;

/* @var $this yii\web\View */
/* @var $model backend\models\Menu */
/* @var $form ActiveForm */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '自动返还';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 自动返还设置</span>
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

        <div class="form-group field-channel-status">
            <div><label class="" for="channel-status">开启状态</label></div>
            <input type="hidden" name="Return[value]" value="">
            <label class="mt-radio mt-radio-outline" style="padding-right:20px;margin-bottom:5px;"><input type="radio" name="Return[value]" value="1" <?php if($model['value'] == 1) { ?>checked=""<?php } ?>><span></span> 开启</label>
            <label class="mt-radio mt-radio-outline" style="padding-right:20px;margin-bottom:5px;"><input type="radio" name="Return[value]" value="0" <?php if($model['value'] == 0) { ?>checked=""<?php } ?>><span></span> 关闭</label><span class="help-block"></span>
        </div>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 保存', ['id' => 'sub','class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <!-- END FORM-->
    </div>
</div>

<?php
AppAsset::register($this);
//只在该视图中使用非全局的jui
AppAsset::addScript($this,'static/js/ajaxfileupload.js');
?>

<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>

$(function() {
/* 子导航高亮 */
highlight_subnav('return/config');
});

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
