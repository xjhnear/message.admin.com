<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class MessageDetail extends \common\modelsgii\MessageDetail
{

    public function rules()
    {
        return [
            [['phonenumber', 'message_id', 'message_code'], 'required'],
            [['message_id', 'send_time', 'return_time', 'status', 'create_uid'], 'integer'],
            [['phonenumber', 'message_code'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /* 在rules验证前，时间自动完成 */
//            [
//                'class' => 'yii\behaviors\AttributeBehavior',
//                'attributes' => [
//                    static::EVENT_BEFORE_VALIDATE => 'create_time',
//                ],
//                'value' => time(),
//            ],
        ];
    }
}
