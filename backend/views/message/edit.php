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
$this->title = '发短信';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 短信配置</span>
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

        <div class="form-group field-message-phonenumbers" style="height: 200px;">
            <div style="margin-top:10px;margin-bottom:10px;">
                <span class="btn red btn-outline btn-file">
                    <span id="fileup" class="fileinput-new"> 上传文件 </span>
                    <input id="fileUpload" type="file" name="fileUpload" style="display: none" onchange="ajaxUploadFile()" accept="text/plain,.csv" />
                </span>
            </div>
            <div style="width: 25%;float: left;">
                <div><label class="" for="message-phonenumbers">手机号码</label><span class="help-inline">（多个号码之间","隔开）</span></div><textarea id="message-phonenumbers" class="form-control c-md-9" name="Message[phonenumbers]" rows="6"><?=$model->phonenumbers ?></textarea><span class="help-block"></span>
                <input id="message-phonenumbers_json" type="hidden" name="Message[phonenumbers_json]" value="<?=$model->phonenumbers_json ?>">
            </div>
            <div style="width: 75%;float: left;">
                <div><label class="" for="message-content">短信内容</label><span class="help-inline"></span></div><textarea id="message-content" class="form-control c-md-7" name="Message[content]" rows="5" onkeyup="checkLen(this)"><?=$model->content ?></textarea><span class="help-block"></span>
                <div class="help-inline">您已经输入 <span id="count">0</span> 个文字</div>
            </div>
        </div>
        <div class="form-group field-message-content">
            <input type="hidden" id="rest" value="<?=$data["rest"] ?>">
            <div class="help-inline" id="phone_msg" style="display: none;">
                共导入 <span id="phone_count">0</span> 个号码 (联通: <span id="phone_count_unicom">0</span> 个 移动: <span id="phone_count_mobile">0</span> 个 电信: <span id="phone_count_telecom">0</span> 个)
                <b id="rest_error" style="display: none;color: #e7505a;padding-left: 21px;"> * 您目前的余额只能发送 <span id="phone_rest"><?=$data["rest"] ?></span> 个号码</b>
            </div>
        </div>

        <div class="form-group field-message-content" style="display: none" >
            <div><label class="" for="message-content">短信内容</label><span class="help-inline">（移动）*为空默认同联通</span></div><textarea id="message-content1" class="form-control c-md-7" name="Message[content1]" rows="5" onkeyup="checkLen1(this)"><?=$model->content ?></textarea><span class="help-block"></span>
            <div class="help-inline">您已经输入 <span id="count1">0</span> 个文字</div>
        </div>

        <div class="form-group field-message-content" style="display: none" >
            <div><label class="" for="message-content">短信内容</label><span class="help-inline">（电信）*为空默认同联通</span></div><textarea id="message-content2" class="form-control c-md-7" name="Message[content2]" rows="5" onkeyup="checkLen2(this)"><?=$model->content ?></textarea><span class="help-block"></span>
            <div class="help-inline">您已经输入 <span id="count2">0</span> 个文字</div>
        </div>

        <?=$form->field($model, 'send_time')->widget(\kartik\widgets\DateTimePicker::classname(),[
            'language' => 'zh-CN',
            'type' => \kartik\widgets\DateTimePicker::TYPE_INPUT,
            'value' => '2016-07-15',
            'options' => ['class' => 'form-control'],
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd hh:ii',
            ]
        ],['class' => 'c-md-2'])->label('发送时间')->hint('')?>

        <div class="form-actions">
            <?= Html::submitButton('<i class="icon-ok"></i> 提交', ['id' => 'sub','class' => 'btn blue ajax-post','target-form'=>'form-aaa']) ?>
            <?= Html::button('取消', ['class' => 'btn','onclick'=>'JavaScript:history.go(-1)']) ?>
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
highlight_subnav('message/add');
checkLen(document.getElementById("message-content"))
checkLen1(document.getElementById("message-content1"))
checkLen2(document.getElementById("message-content2"))
});

// 短信内容字数统计
function checkLen(obj)
{
var curr = obj.value.length;
document.getElementById("count").innerHTML = curr.toString();
}
function checkLen1(obj)
{
var curr = obj.value.length;
document.getElementById("count1").innerHTML = curr.toString();
}
function checkLen2(obj)
{
var curr = obj.value.length;
document.getElementById("count2").innerHTML = curr.toString();
}

// 定义的热点被单击则打开文件选择框
$('#fileup').on('click', function()
{
UploadFileOnSelect();
});

// 选需要上载的图片 上载完毕清除 form
function UploadFileOnSelect()
{
// 打开文件选择框
var input = document.getElementById("fileUpload");
input.click();
}

function ajaxUploadFile()
{
// 当 file 框内容改变则提交 form
// $('#formUpload').submit();

var token = "<?php echo \Yii::$app->request->getCsrfToken()?>";
//$("#jUploadFormfileUpload").remove();
//var type=$("input[name='type']:checked").val();
//var formId = 'jUploadForm' + 'fileUpload';  //file为input的id
//var test1 = jQuery('#'+formId);
//console.log("1:"+test1.prop("outerHTML"));//打印输出

$.ajaxFileUpload({
url: '/message/get-ajax',
secureuri: false,
cache:false,
data:{_csrf: token},
fileElementId:'fileUpload',
dataType: 'json',
success: function (data) {
if (data.state) {
//上传成功
data.phone = data.phone.replace(/,/g,",\n");
document.getElementById("message-phonenumbers").innerHTML = data.phone;
document.getElementById("phone_count").innerHTML = data.phone_count.all;
document.getElementById("phone_count_unicom").innerHTML = data.phone_count.unicom;
document.getElementById("phone_count_mobile").innerHTML = data.phone_count.mobile;
document.getElementById("phone_count_telecom").innerHTML = data.phone_count.telecom;
document.getElementById("message-phonenumbers_json").value = data.phone_json;
$("#phone_msg").show();
if (data.phone_count.all > $('#rest').val()) {
$("#rest_error").show();
$("#sub").attr("disabled", true);
} else {
$("#sub").attr("disabled", false);
}
$('form')[0].reset();
} else {
alert(data.msg);
}
},
error: function (data, status, e) {
return;
}
});
}


<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
