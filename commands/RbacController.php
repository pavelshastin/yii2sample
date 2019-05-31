<?php

namespace app\commands;

use Yii;
use yii\console\Controller;


class RbacController extends Controller {
	

	public function actionInit() {

		$auth = Yii::$app->authManager;

		$createPost = $auth->createPermission('createPost');
		$createPost->description = 'add post';
		$auth->add($createPost);

		$updatePost = $auth->createPermission('updatePost');
		$updatePost->description = 'update post';
		$auth->add($updatePost);

		$author = $auth->createRole('author');
		$auth->add($author);
		$auth->addChild($author, $createPost);

		$admin = $auth->createRole('administrator');
		$auth->add($admin);
		$auth->addchild($admin, $updatePost);
		$auth->addChild($admin, $author);


		$auth->assign($author, 1);
		$auth->assign($admin, 2);

	}

}