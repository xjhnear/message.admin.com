<?php

namespace common\modelsgii;

use Yii;

/**
 * This is the model class for table "{{%report_list}}".
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
class Report extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%report}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['c_date', 'create_time'], 'required'],
            [['send_count', 'create_time', 'success_count', 'uid'], 'integer'],
            [['c_date'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'report_id' => 'Report ID',
            'uid' => 'UID',
            'c_date' => 'C Date',
            'send_count' => 'Send Count',
            'success_count' => 'Success Count',
            'create_time' => 'Create Time',
        ];
    }
}
