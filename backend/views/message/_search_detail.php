<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['detail'],
    'method' => 'get',
    'options'=>[
        //'class'=>"form-inline",
        'data-pjax' => true, //开启pjax搜索
    ]
]); ?>
<div class="row">
    <div class="col-md-2">
    <?= $form->field($model, 'message_code')->textInput()->label('批次号') ?>
    </div>
    <div class="col-md-2">
    <?=$form->field($model, 'operator')->dropDownList([''=>'全部',1 => '联通',2 => '移动',3 => '电信',4 => '其他'],['class'=>'form-control'])->label('运营商'); ?>
    </div>
    <div class="col-md-2">
    <?=$form->field($model, 'status')->dropDownList([''=>'全部',0 => '待审核',1 => '审核通过',2 => '审核拒绝',3 => '发送成功',4 => '发送失败',5 => '发送中'],['class'=>'form-control'])->label('状态'); ?>
    </div>
    <input type="hidden" name="pid" value="<?=Yii::$app->request->getQueryParam('pid') ?>">
    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
