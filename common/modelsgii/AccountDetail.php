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
class AccountDetail extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_detail}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'change_count', 'balance', 'op_uid'], 'required'],
            [['uid', 'change_count', 'balance', 'op_uid', 'create_time', 'change_type'], 'integer'],
            [['remark'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_did' => 'Account DID',
            'uid' => 'UID',
            'change_count' => 'Change Count',
            'change_type' => 'Change Type',
            'balance' => 'Balance',
            'remark' => 'Remark',
            'create_time' => 'Create Time',
            'op_uid' => 'OP UID',
        ];
    }
}
