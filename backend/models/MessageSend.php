<?php

namespace backend\models;

use Yii;

/**
 * ---------------------------------------
 * 文章模型
 * ---------------------------------------
 */
class MessageSend extends \common\modelsgii\MessageSend
{

    public function rules()
    {
        return [
            [['message_id', 'message_did', 'task_id', 'operator', 'channel_id', 'status', 'return_time'], 'integer'],
            [['phonenumber', 'errorcode', 'extno'], 'string']
        ];
    }

    public static function getSubtotal()
    {
        $out_arr = array();
        $out_arr['unicom'] = static::findAll(['operator' => 1, 'status' => 1, 'is_del' => 0]);
        $out_arr['mobile'] = static::findAll(['operator' => 2, 'status' => 1, 'is_del' => 0]);
        $out_arr['telecom'] = static::findAll(['operator' => 3, 'status' => 1, 'is_del' => 0]);
        return $out_arr;
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
