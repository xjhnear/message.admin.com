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
class MessageDetail extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phonenumber', 'message_id', 'message_code'], 'required'],
            [['message_id', 'send_time', 'return_time', 'status'], 'integer'],
            [['phonenumber', 'message_code'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_did' => 'Message DID',
            'phonenumber' => 'Phonenumber',
            'message_id' => 'Message ID',
            'message_code' => 'Message Code',
            'send_time' => 'Send Time',
            'return_time' => 'Return Time',
            'status' => 'Status',
            'channel_id' => 'Channel ID',
        ];
    }
}
