<?php

namespace common\modelsgii;

use Yii;

/**
 * This is the model class for table "{{%channel}}".
 *
 * @property string $channel_id
 * @property string $name
 * @property string $userid
 * @property string $account
 * @property string $password
 * @property string $operator
 */
class Channel extends \common\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%channel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account', 'password', 'operator'], 'required'],
            [['userid', 'operator', 'status', 'is_del'], 'integer'],
            [['account', 'password', 'name'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'channel_id' => 'Channel ID',
            'name' => 'Name',
            'userid' => 'Userid',
            'account' => 'Account',
            'password' => 'Password',
            'operator' => 'Operator',
            'status' => 'Status',
            'is_del' => 'Is Del',
        ];
    }
}
