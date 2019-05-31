<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\models\User;


class SignupForm extends Model {

	public $name;
	public $password;
	public $role;


	public function rules(){

		return [
			[['name', 'password', 'role'], 'required'],

		];

	}


	public function signup() {

		if ($this->validate()) {
			$user = new User();

			$user->username = $this->name;
			$user->setPassword($this->password);
			$user->generateAuthKey();
			$user->save();

			$auth = Yii::$app->authManager;
			$authorRole = $auth->getRole('author');
			$auth->assign($authorRole, $user->getId());
			
			return $user;
		}


		return null;
	}

}