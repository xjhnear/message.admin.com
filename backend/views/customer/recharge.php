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
$this->title = '商户充值';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

?>

<div class="portlet light bordered">
    <div class="portlet-title">
        <div class="caption font-red-sunglo">
            <i class="icon-settings font-red-sunglo"></i>
            <span class="caption-subject bold uppercase"> 充值信息</span>
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
            <div><label class="" for="message-content">用户名</label><span class="help-inline"></span> <span><?=$model->username ?></span></div><span class="help-block"></span>
        </div>

        <div class="form-group field-message-content">
            <div><label class="" for="message-content">单价</label><span class="help-inline"></span> <span><?=$model->coefficient ?></span> 元/条 </div><span class="help-block"></span>
            <input type="hidden" name="balance" value="<?=$model->balance ?>">
        </div>

        <div class="form-group field-admin-status">
            <div><label class="" for="admin-status">充值类型</label></div>
            <input type="hidden" name="Recharge[type]" value="">
            <label class="mt-radio mt-radio-outline" style="padding-right:20px;margin-bottom:5px;"><input type="radio" name="Recharge[type]" class="rtype" value="1" checked=""><span></span> 充值</label>
            <label class="mt-radio mt-radio-outline" style="padding-right:20px;margin-bottom:5px;"><input type="radio" name="Recharge[type]" class="rtype" value="0"><span></span> 返还</label><span class="help-block"></span>
        </div>

        <div class="form-group field-admin-username required has-success">
            <div><label class="" for="admin-username">充值金额</label></div>
            <div class="left"><input type="text" id="recharge-balance" class="form-control c-md-3" name="Recharge[balance]" value="" onkeyup="value=value.replace(/[^\d]/g,'');checkBalance(this)" aria-invalid="false"></div><span class="help-block"></span>
        </div>

        <div class="form-group field-admin-username required has-success">
            <div><label class="" for="admin-username">充值短信条数</label></div>
            <div class="left"><input type="text" id="recharge-count" class="form-control c-md-3" name="Recharge[count]" value="" onkeyup="value=value.replace(/[^\d]/g,'');checkCount(this)" aria-invalid="false"></div><span class="help-block"></span>
        </div>

        <div class="form-group field-admin-username required has-success">
            <div><label class="" for="admin-username">备注</label></div>
            <div class="left"><input type="text" id="recharge-userremark" class="form-control c-md-3" name="Recharge[userremark]" value="预充值" aria-invalid="false"></div><span class="help-block"></span>
        </div>

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

    $(".rtype").on('click',function(){
    if($(this).val() == 1){
    $("#recharge-userremark").val('预充值');
    }else{
    $("#recharge-userremark").val('失败返还');
    }
    })

});

function checkBalance(obj)
{
var balance = obj.value;
var coefficient = <?=$model->coefficient ?>;
var count = Math.floor(balance / coefficient);
document.getElementById("recharge-count").value = count;
}

function checkCount(obj)
{
var count = obj.value;
var coefficient = <?=$model->coefficient ?>;
var balance = Math.ceil(count * coefficient);
document.getElementById("recharge-balance").value = balance;
}

<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
