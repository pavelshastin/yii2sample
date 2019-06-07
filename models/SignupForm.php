<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;


class SignupForm extends Model {

	public $username;
	public $password;
	public $email;


	public function rules(){

		return [
			['username', 'filter', 'filter' => 'trim'],
			['username', 'required'],
			['username', 'unique', 
			  'targetClass' => 'app\models\User',
			  'message' => 'This username has been already taken'
			],
			['username', 'string', 'min' => 2, 'max' => 255],

			['password', 'required'],
			['password', 'string', 'min' => 6],

			['email', 'filter', 'filter' => 'trim'],
			['email', 'required'],
			['email', 'unique', 
				'targetClass' => 'app\models\User',
				'message' => 'This email has been taken'
			],
			['email', 'email']

		];

	}



	public function signup() {

		if ($this->validate()) {
			$user = new User();

			$user->username = $this->username;
			$user->email = $this->email;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			$user->save();

			// $auth = Yii::$app->authManager;
			// $authorRole = $auth->getRole('author');
			// $auth->assign($authorRole, $user->getId());
			
			return $user;
		}


		return null;
	}

}