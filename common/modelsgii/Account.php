<?php

namespace common\modelsgii;

use Yii;

/**
 * This is the model class for table "{{%account_list}}".
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
class Account extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_date', 'create_time'], 'required'],
            [['recharge_count', 'create_time', 'consume_count', 'fail_count'], 'integer'],
            [['c_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'account_id' => 'Account ID',
            'c_date' => 'C Date',
            'recharge_count' => 'Recharge Count',
            'consume_count' => 'Consume Count',
            'fail_count' => 'Fail Count',
            'create_time' => 'Create Time',
        ];
    }
}
