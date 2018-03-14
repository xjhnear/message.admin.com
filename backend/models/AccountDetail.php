<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class AccountDetail extends \common\modelsgii\AccountDetail
{

    public function rules()
    {
        return [
            [['uid', 'change_count', 'balance', 'op_uid'], 'required'],
            [['uid', 'change_count', 'balance', 'op_uid', 'create_time', 'change_type'], 'integer'],
            [['remark', 'userremark'], 'string']
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
