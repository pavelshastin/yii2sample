<?php




class m190522_153951_create_user_table extends \yii\db\Migration {

	public function up() {
		
		$this->createTable('test_users', [
			'id' => $this->primaryKey(),
			'username' => $this->string(64)->notNull(),
			'password' => $this->string(255)->notNull(),
			'rbac_role' => $this->string(50),

		]);	

	}


	public function down() {
		
		$this->dropTable('test_users');
	}


}