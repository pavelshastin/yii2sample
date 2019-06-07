<?php

namespace app\models\helpers;

use Yii;
use yii\helpers\Url;

use app\models\helpers\ValueHelpers;


class PermissionHelpers {


	public static function userMustBeOwner($model_name, $model_id) {

		$conn = \Yii::$app->db;
		$userId = Yii::$app->user->identity->id;

		$sql = "SELECT id FROM $model_name WHERE user_id=:userId AND id=:model_id";
		$command = $conn->createCommand($sql);
		$command->bindValue(":userId", $userId);
		$command->bindValue(":model_id", $model_id);

		if ($result = $command->queryOne()) {

			return true;
		} else {

			return false;
		}

	}


	public static function requireUpgradeTo($user_type_name){
		if (Yii::$app->user->identity->id) != ValueHelpers::getUserTypeValue($user_time_name)) {

			return Yii::$app->getResponse()->redirect(Url::to(['upgrade/index']));
		} 

	} 


	public static function requireStatus($status_name) {
		if (Yii::$app->user->identity->status_id == ValueHelpers->getStatusValue($status_name)) {

			return true;
		} else {

			return false;
		}

	}


	public static function requireMinimumStatus($status_name) {
		if (Yii::$app->user->identity->status_id >= ValueHelpers->getStatusValue($status_name)) {

			return true;
		} else {

			return false;
		}

	}


	public static function requireRole($role_name){
		if (Yii::$app->user->identity->role_id == ValueHelpers->getStatusValue($role_name)) {

			return true;
		} else {

			return false;
		}
	}


	public static function requireMinimumRole($role_name) {
		if (Yii::$app->user->identity->role_id >= ValueHelpers->getStatusValue($role_name)) {

			return true;
		} else {

			return false;
		}

	}


}