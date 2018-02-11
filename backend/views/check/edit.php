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
$this->title = '审核管理';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 详细信息</span>
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

        <div class="form-group field-message-content">
            <div><label class="" for="message-content">短信内容</label><span class="help-inline"></span></div><textarea id="message-content" class="form-control c-md-7" name="Message[content]" rows="5" onkeyup="checkLen(this)"><?=$model->content ?></textarea><span class="help-block"></span>
            <div class="help-inline">您已经输入 <span id="count">0</span> 个文字</div>
        </div>

        <div class="form-group field-message-content">
            <div><label class="" for="message-content">手机号码数量</label><span class="help-inline"></span> <span><?=$model->count ?></span> 条</div><span class="help-block"></span>
        </div>

        <div class="form-group field-message-phonenumbers">
            <div><label class="" for="message-phonenumbers">通道选择</label><span class="help-inline"></span></div><span class="help-block"></span>
            <table class="table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer">
                <colgroup>
                    <col width="150px;">
                    <col width="150px;">
                </colgroup>
                <tbody>
                    <tr><td style="vertical-align: middle;">联通</td><td style="vertical-align: middle;"><span><?=$model->phonenumbers_json['unicom'] ?></span> 条</td><td><select id="message-status" class="form-control" name="Message[status_unicom]" aria-invalid="false" style="width: 30%;"><option value="1">默认通道</option></select></td></tr>
                    <tr><td style="vertical-align: middle;">移动</td><td style="vertical-align: middle;"><span><?=$model->phonenumbers_json['mobile'] ?></span> 条</td><td><select id="message-status" class="form-control" name="Message[status_mobile]" aria-invalid="false" style="width: 30%;"><option value="2">默认通道</option></select></td></tr>
                    <tr><td style="vertical-align: middle;">电信</td><td style="vertical-align: middle;"><span><?=$model->phonenumbers_json['telecom'] ?></span> 条</td><td><select id="message-status" class="form-control" name="Message[status_telecom]" aria-invalid="false" style="width: 30%;"><option value="3">默认通道</option></select></td></tr>
                </tbody>
            </table>
        </div>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 审核通过', ['class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
            <?= Html::button('不通过', ['class' => 'btn red','onclick'=>'JavaScript:doReject()']) ?>
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
    highlight_subnav('message/index');
    checkLen(document.getElementById("message-content"))
});

// 短信内容字数统计
function checkLen(obj)
{
    var curr = obj.value.length;
    document.getElementById("count").innerHTML = curr.toString();
}

function doReject()
{
    var id = <?=$model->message_id?>;
    window.location.href='/check/reject?id='+id;
}


<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
