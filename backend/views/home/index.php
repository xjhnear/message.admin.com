<?php
/* ===========================以下为本页配置信息================================= */
/* 页面基本属性 */
$this->title = '主页信息';
$this->params['title_sub'] = '';

/* 渲染其他文件 */
//echo $this->renderFile('@app/views/public/login.php');

/* 加载页面级别JS */
//$this->registerJsFile('@web/static/common/js/app.js');

?>

<div class="note note-info">
    <h4> 欢迎使用短信系统！</h4>
    <?php if(Yii::$app->user->identity->role == 1) { ?>
    您的账户尚有余额：<b <?php if(($dataProvider['balance']/$dataProvider['coefficient']) <= 100){?> style="color: #e7505a" <?php }else{ ?> style="color: #3598dc"<?php } ?>><?=($dataProvider['balance']/$dataProvider['coefficient'])?></b> 条
    <?php } ?>
</div>

<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 blue" href="#">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value=""><?=$dataProvider['subtotal_today']?></span>
                </div>
                <div class="desc"> 今日已发短信 </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 red" href="#">
            <div class="visual">
                <i class="fa fa-bar-chart-o"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value=""></span><?=$dataProvider['subtotal_today_per']?>%</div>
                <div class="desc"> 今日短信成功率 </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 green" href="#">
            <div class="visual">
                <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value=""><?=$dataProvider['subtotal_thismonth']?></span>
                </div>
                <div class="desc"> 本月已发短信 </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <a class="dashboard-stat dashboard-stat-v2 purple" href="#">
            <div class="visual">
                <i class="fa fa-globe"></i>
            </div>
            <div class="details">
                <div class="number">
                    <span data-counter="counterup" data-value=""></span><?=$dataProvider['subtotal_thismonth_per']?>%</div>
                <div class="desc"> 本月短信成功率 </div>
            </div>
        </a>
    </div>
</div>
<div class="clearfix"></div>

<?php if(Yii::$app->user->identity->role == 1) { ?>
<div class="row-fluid margin-bottom-30">
    <div class="span6">
        <blockquote class="hero">
            <p>您已超过95%的用户</p>
            <!--<small>Bob Nilson</small>-->
        </blockquote>
    </div>
</div>
<?php } ?>

<!-- 定义数据块 -->
<?php $this->beginBlock('test'); ?>
jQuery(document).ready(function() {
highlight_subnav('home/index'); //子导航高亮
});
<?php $this->endBlock() ?>
<!-- 将数据块 注入到视图中的某个位置 -->
<?php $this->registerJs($this->blocks['test'], \yii\web\View::POS_END); ?>
