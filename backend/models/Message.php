<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class Message extends \common\modelsgii\MessageList
{

    public function rules()
    {
        return [
            [['phonenumbers', 'content', 'send_time'], 'required'],
            [['create_time', 'check_time', 'status', 'count'], 'integer'],
            [['phonenumbers', 'content', 'message_code', 'create_name', 'check_name'], 'string']
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
