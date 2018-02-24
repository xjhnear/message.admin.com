<?php

namespace backend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
/**
 * 实现User组件中的身份识别类 参见 yii\web\user
 * This is the model class for table "{{%channel}}".
 *
 * @property string $channel_id
 * @property string $name
 * @property string $userid
 * @property string $account
 * @property string $password
 * @property string $operator
 */
class Channel extends \common\modelsgii\Channel
{

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
