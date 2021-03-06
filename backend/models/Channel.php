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
            [['userid', 'account', 'password', 'operator', 'url'], 'required'],
            [['operator', 'status', 'is_del', 'type'], 'integer'],
            [['account', 'password', 'name', 'url'], 'string']
        ];
    }

    public static function getChannelList()
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
