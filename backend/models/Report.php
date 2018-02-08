<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class Report extends \common\modelsgii\Report
{

    public function rules()
    {
        return [
            [['c_date', 'create_time'], 'required'],
            [['send_count', 'create_time', 'success_count', 'uid'], 'integer'],
            [['c_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /* 在rules验证前，时间自动完成 */
            [
                'class' => 'yii\behaviors\AttributeBehavior',
                'attributes' => [
                    static::EVENT_BEFORE_VALIDATE => 'create_time',
                ],
                'value' => time(),
            ],
        ];
    }
}
