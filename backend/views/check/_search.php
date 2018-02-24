<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\search\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['index'],
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
    <div class="col-md-5">
        <?= Html::activeHiddenInput($model,'status') ?>
        <div class="btn-group btn-group-devided" style="margin-top: 24px;">
            <?=Html::a('待审核 <i class="fa"></i>',['check/index?MessageCheckSearch[status]=0'],['class'=>'btn blue'])?>
            <?=Html::a('审核通过 <i class="fa"></i>',['check/index?MessageCheckSearch[status]=1'],['class'=>'btn blue'])?>
            <?=Html::a('审核拒绝 <i class="fa"></i>',['check/index?MessageCheckSearch[status]=2'],['class'=>'btn blue'])?>
            <?=Html::a('全部 <i class="fa"></i>',['check/index'],['class'=>'btn blue'])?>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group" style="margin-top: 24px;">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
