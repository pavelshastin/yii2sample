<?php

namespace app\models;


class ValueHelpers {


	public static function getRoleValue($role_name){
		$conn = \Yii::$app->db;
		$sql = "SELECT role_value FROM role WHERE role_name =:role_name";
		$command = $conn->createCommand($sql);
		$command->bindValue(":role_name", $role_name);
		$result = $command->queryOne($command);

		return $result['role_value'];

	}


	public static function getStatusValue($status_name){
		$conn = \Yii::$app->db;
		$sql = "SELECT status_value FROM status WHERE status_name =:status_name";
		$command = $conn->createCommand($sql)
		$command->bindValue(":status_name", $status_name);
		$result = $conn->queryOne($sql)

		return $result['status_value'];
	}


	public static function getUserTypeValue($user_type_name){
		$conn = \Yii::$app->db;
		$sql = "SELECT status_value FROM status WHERE user_type_name =:user_type_name";
		$command = $conn->createCommand($sql)
		$command->bindValue(":user_type_name", $user_type_name);
		$result = $conn->queryOne($sql)

		return $result['user_type_value'];
	}







}