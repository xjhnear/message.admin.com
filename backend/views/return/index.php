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
$this->title = '手动返还';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 返还设置</span>
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

        <div class="form-group field-message-phonenumbers">
            <div><label class="" for="message-phonenumbers">返还信息</label><span class="help-inline"></span></div><span class="help-block"></span>
            <input type="hidden" name="Config[model_json]" value='<?=$model_json ?>'>
            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer">
                <colgroup>
                    <col width="20px;">
                    <col width="100px;">
                    <col width="150px;">
                    <col width="100px;">
                    <col width="150px;">
                </colgroup>
                <thead>
                <tr>
                    <th><label class="mt-checkbox mt-checkbox-outline" style="padding-left:19px;"><input type="checkbox" class="select-on-check-all" name="id_all" value="1"><span></span></label></th>
                    <th>ID</th><th>批次号</th><th>用户ID</th><th>待返还数量</th><th>发送时间</th></tr>
                </thead>
                <tbody>
                <?php if(count($model)> 0) { ?>
                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td><label class="mt-checkbox mt-checkbox-outline" style="padding-left:19px;"><input type="checkbox" name="id[]" value="<?=$item['message_id'] ?>"> <span></span></label></td>
                        <td style="vertical-align: middle;"><?=$item['message_id'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['message_code'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['create_uid'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['balance'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['send_time'] ?></td>
                    </tr>
                <?php } ?>
                <?php }else{ ?>
                    <tr><td colspan="6"><div class="empty">没有找到数据。</div></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 确认返还', ['id' => 'sub','class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
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
highlight_subnav('return/index');

$('#sub').click(function(){
if(confirm('确认返还？'))
{
return true;
}else{
return false;
}
});
});

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
