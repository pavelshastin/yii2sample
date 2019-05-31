<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\SignupForm;



class SignupController extends Controller {

	public function actionIndex() {

		$this->render('index');
	}


	public function actionSignup() {

		if (!$app->user->isGuest) {
			$this->goHome();
		}

		$model = new SignupForm();

		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			
			
			Yii::$app->session->setFlash('signupFormSubmitted');
			$this->refresh();

		}

		return $this->render('signup', ['model' => $model]);

	}

}