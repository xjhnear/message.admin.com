<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $model common\modelsgii\Account */
/* @var $dataProvider yii\data\ActiveDataProvider  */
/* @var $searchModel backend\models\search\AccountSearch */

/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '通道管理';
$this->params['title_sub'] = '';  // 在\yii\base\View中有$params这个可以在视图模板中共享的参数

/* 先要注册表格所须的资源 */
\backend\assets\TablesAsset::register($this);

$columns = [
    [
        'class' => \common\core\CheckboxColumn::className(),
        'name'  => 'channel_id',
        'options' => ['width' => '20px;'],
        'checkboxOptions' => function ($model, $key, $index, $column) {
            return ['value' => $key,'label'=>'<span></span>','labelOptions'=>['class' =>'mt-checkbox mt-checkbox-outline','style'=>'padding-left:19px;']];
        }
    ],
    [
        'header' => '通道名称',
        'value' => 'name',
        'options' => ['width' => '50px;']
    ],
    [
        'header' => '账号',
        'value' => 'account',
        'options' => ['width' => '50px;']
    ],
    [
        'header' => '运营商',
        'value' => 'operator',
        'options' => ['width' => '80px;'],
        'content' => function($model){
            return Yii::$app->params['operator_status'][$model['operator']];
        },
    ],
    [
        'header' => '状态',
        'value' => 'status',
        'options' => ['width' => '70px;'],
        'content' => function($model){
            return $model['status'] ?
                Html::tag('span','正常',['class'=>'label label-sm label-success']) :
                Html::tag('span','删除',['class'=>'label label-sm label-danger']);
        }
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'header' => '操作',
        'template' => '{edit} {delete}',
        'options' => ['width' => '100px;'],
        'buttons' => [
            'edit' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-edit"></i> 更新', ['edit','uid'=>$key], [
                    'title' => Yii::t('app', '更新'),
                    'class' => 'btn btn-xs blue'
                ]);
            },
            'delete' => function ($url, $model, $key) {
                return Html::a('<i class="fa fa-times"></i>', ['delete', 'id'=>$key], [
                    'title' => Yii::t('app', '删除'),
                    'class' => 'btn btn-xs red ajax-get confirm'
                ]);
            }
        ],
    ],
];

?>

<div class="portlet light portlet-fit portlet-datatable bordered">
    <div class="portlet-title">
        <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase">通道列表</span>
        </div>
        <div class="actions">
            <div class="btn-group btn-group-devided">
                <?=Html::a('添加 <i class="fa fa-plus"></i>',['add'],['class'=>'btn green'])?>
                <?=Html::a('清空搜索 <i class="fa fa-times"></i>',['channel/index'],['class'=>'btn green','style'=>'margin-right:10px;'])?>
            </div>
        </div>
    </div>
    <div class="portlet-body">
        <?php \yii\widgets\Pjax::begin(['options'=>['id'=>'pjax-container']]); ?>
        <div>
            <?php //echo $this->render('_search', ['model' => $searchModel]); ?> <!-- 条件搜索-->
        </div>
        <div class="table-container">
            <form class="ids">
            <?= GridView::widget([
                'dataProvider' => $dataProvider, // 列表数据
                'filterModel' => $searchModel, // 搜索模型
                'options' => ['class' => 'grid-view table-scrollable'],
                /* 表格配置 */
                'tableOptions' => ['class' => 'table table-striped table-bordered table-hover table-checkable order-column dataTable no-footer'],
                /* 重新排版 摘要、表格、分页 */
                'layout' => '{items}<div class=""><div class="col-md-5 col-sm-5">{summary}</div><div class="col-md-7 col-sm-7"><div class="dataTables_paginate paging_bootstrap_full_number" style="text-align:right;">{pager}</div></div></div>',
                /* 配置摘要 */
                'summaryOptions' => ['class' => 'pagination'],
                /* 配置分页样式 */
                'pager' => [
                    'options' => ['class'=>'pagination','style'=>'visibility: visible;'],
                    'nextPageLabel' => '>>',
                    'prevPageLabel' => '<<',
                    'firstPageLabel' => '首页',
                    'lastPageLabel' => '尾页',
                    'maxButtonCount'=> 5
                ],
                /* 定义列表格式 */
                'columns' => $columns,
            ]); ?>
            </form>
        </div>
        <?php \yii\widgets\Pjax::end(); ?>
    </div>
</div>


<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
jQuery(document).ready(function() {
    highlight_subnav('channel/index'); //子导航高亮
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
