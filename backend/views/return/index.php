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
                    <col width="100px;">
                    <col width="150px;">
                </colgroup>
                <thead>
                <tr>
                    <th></th>
                    <th>ID</th><th>批次号</th><th>用户名</th><th>待返还数量</th><th>发送时间</th><th>操作</th></tr>
                </thead>
                <tbody>
                <?php if(count($model)> 0) { ?>
                <?php foreach ($model as $item) { ?>
                    <tr>
                        <td><label class="mt-checkbox mt-checkbox-outline" style="padding-left:19px;"><input type="checkbox" name="ids" value="<?=$item['message_id'] ?>"> <span></span></label></td>
                        <td style="vertical-align: middle;"><?=$item['message_id'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['message_code'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['create_name'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['balance'] ?></td>
                        <td style="vertical-align: middle;"><?=$item['send_time'] ?></td>
                        <td style="vertical-align: middle;">
                        <?= Html::button('返还', ['class' => 'btn blue','onclick'=>'JavaScript:doOk('.$item['message_id'].')']) ?>
                        <?= Html::button('已返还', ['class' => 'btn red','onclick'=>'JavaScript:doReject('.$item['message_id'].')']) ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php }else{ ?>
                    <tr><td colspan="7"><div class="empty">没有找到数据。</div></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <?= Html::button('批量返还', ['class' => 'btn blue','onclick'=>'JavaScript:doOkall()']) ?>
            <?= Html::button('批量已返还', ['class' => 'btn red','onclick'=>'JavaScript:doRejectall()']) ?>
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

});

function doOk(id)
{
if(confirm('确认返还？'))
{
window.location.href='/return/ok?id='+id;
return true;
}else{
return false;
}
}
function doReject(id)
{
if(confirm('确认已返还？'))
{
window.location.href='/return/reject?id='+id;
return true;
}else{
return false;
}
}

function doOkall()
{
ids =  $("input:checkbox[name='ids']:checked").map(function(index,elem) {
return $(elem).val();
}).get().join(',');
if(confirm('确认返还？'))
{
window.location.href='/return/okall?ids='+ids;
return true;
}else{
return false;
}
}
function doRejectall()
{
ids =  $("input:checkbox[name='ids']:checked").map(function(index,elem) {
return $(elem).val();
}).get().join(',');
if(confirm('确认已返还？'))
{
window.location.href='/return/rejectall?ids='+ids;
return true;
}else{
return false;
}
}

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
