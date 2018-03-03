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
            [['userid', 'account', 'password', 'operator', 'url'], 'required'],
            [['operator', 'status', 'is_del', 'type'], 'integer'],
            [['account', 'password', 'name', 'url'], 'string']
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
            'url' => 'Url',
            'operator' => 'Operator',
            'status' => 'Status',
            'is_del' => 'Is Del',
            'type' => 'Type',
        ];
    }
}
