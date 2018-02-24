<?php

namespace common\modelsgii;

use Yii;

/**
 * This is the model class for table "{{%message_list}}".
 *
 * @property integer $order_id
 * @property string $order_sn
 * @property integer $uid
 * @property string $name
 * @property string $tel
 * @property string $sfz
 * @property string $type
 * @property integer $aid
 * @property string $title
 * @property integer $province
 * @property integer $city
 * @property integer $area
 * @property integer $start_time
 * @property integer $end_time
 * @property integer $num
 * @property integer $pay_status
 * @property integer $pay_time
 * @property integer $pay_type
 * @property integer $pay_source
 * @property integer $create_time
 * @property integer $status
 */
class MessageSend extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_send}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id', 'message_did', 'task_id', 'operator', 'channel_id', 'status', 'return_time', 'create_time', 'uid'], 'integer'],
            [['phonenumber', 'errorcode', 'extno'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_sid' => 'Message SID',
            'message_id' => 'Message ID',
            'message_did' => 'Message DID',
            'phonenumber' => 'Phonenumber',
            'task_id' => 'Task ID',
            'operator' => 'Operator',
            'channel_id' => 'Channel ID',
            'status' => 'Status',
            'return_time' => 'Return Time',
            'errorcode' => 'Errorcode',
            'extno' => 'Extno',
            'create_time' => 'Create Time',
            'uid' => 'UID',
        ];
    }
}
