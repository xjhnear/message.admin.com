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
class MessageCall extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message_call}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phonenumber'], 'required'],
            [['return_time', 'create_time', 'uid'], 'integer'],
            [['phonenumber', 'task_id', 'content'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_cid' => 'Message CID',
            'phonenumber' => 'Phonenumber',
            'task_id' => 'Task ID',
            'content' => 'Content',
            'return_time' => 'Return Time',
            'create_time' => 'Create Time',
            'uid' => 'UID',
        ];
    }
}
