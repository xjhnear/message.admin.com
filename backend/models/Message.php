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
            [['content'], 'required'],
            [['create_time', 'check_time', 'status', 'count', 'create_uid', 'check_uid', 'retry_pid', 'is_retry'], 'integer'],
            [['send_time'], 'safe'],
            [['content', 'message_code', 'create_name', 'check_name'], 'string']
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
