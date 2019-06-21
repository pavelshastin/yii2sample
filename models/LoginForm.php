<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

use app\models\helpers\ValueHelpers;
use app\models\User;
/**
 * LoginForm is the model behind the login form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
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
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        
        if ($this->validate() && $this->getUser()) {

           return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {

           $this->_user = User::findByUsername($this->username);
        }
        
        return $this->_user;
    }


    public function loginAdmin(){


        if ($this->validate() && $this->getUser()->role_id >= ValueHelpers::getRoleValue('Admin') 
                && $this->getUser()->status_id == ValueHelpers::getStatusValue('Active')) {

            return Yii::$app->user->login($this->getUser(), $this->remmemberMe ? 3600 * 24 * 30: 0);    
        } else {

            return new NotFoundHttpException('You shall not pass.');
        }

    }
}
