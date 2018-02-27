<?php
namespace backend\models;

use Yii;
use common\core\BaseModel;
use yii\captcha\Captcha;

/**
 * Login form
 */
class LoginForm extends BaseModel
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $verifyCode;

    /**
     * @var Admin
     */
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            ['username', 'required','message' => '用户名不能为空'],
            ['password', 'required','message' => '密码不能为空'],
            ['verifyCode', 'required','message' => '验证码不能为空'],
            // password is validated by validatePassword()
            ['password', 'validatePassword','message' => '密码不正确！'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],

            ['verifyCode', 'captcha','captchaAction'=>'/login/captcha','message'=>'验证码不正确！'],
        ];
    }

     /*
     * * @return array customized attribute labels
     */
    public function attributeLabels()
     {
          return [
                    // 'verifyCode' => 'Verification Code',
                    'verifyCode' => '',//在官网的教程里是加上了英文字母，我这里先给去掉了,这里去 掉会不会产生影响因为我还没做接收验证，只做了验证码显示的功能，你们可以自己测试下
             ];
     }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Admin|null
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}
