<?php

namespace app\models\helpers;

use Yii;


class RecordHelpers {


	public static function userHas($model_name){

		$conn = \Yii::$app->db;
		$userId = Yii::$app->user->identity->id;
		$sql = "SELECT * FROM $model_name WHERE user_id =:userId";

		$command = $conn->createCommand($sql);
		$command->bindValue(":userId", $userId);

		$result = $command->queryOne();

		if ($result == null) {
			return false;
		} else {

			return $result['id'];
		}


	}


}