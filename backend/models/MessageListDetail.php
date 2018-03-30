<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class MessageListDetail extends \common\modelsgii\MessageListdetail
{

    public function rules()
    {
        return [
            [['phonenumbers'], 'required'],
            [['message_id'], 'integer'],
            [['phonenumbers', 'phonenumbers_json', 'content_json'], 'string']
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
