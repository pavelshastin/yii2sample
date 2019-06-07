<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = "SignupForm";
//print_r($model);
?>

<div class="site-contact">
	<h1><?= Html::encode($this->title) ?></h1> 

	<?php if (Yii::$app->session->hasFlash('signupFormSubmitted')): ?>
		<p class="alert alert-success">
			Thank you for signing up on our site.
		</p>
	<?php else: ?>
		<p>
			Please, fill the down-going form to register on our site.
		</p>

		<div class="row">
			<div class="col-lg-5">
				<?php $form = ActiveForm::begin(['id' => 'signup-form']); ?>
					<?= $form->field($model, 'username')->textInput(['autofocus' => true]); ?>
					<?= $form->field($model, 'password'); ?>
					<?= $form->field($model, 'email'); ?>

					<div class="form-group">
						<?= Html::submitButton('Signup', ['class' => "btn btn-primary", 'name' => 'signup-button']); ?>
					</div>
				
				<?php ActiveForm::end(); ?>
			</div>
		</div>

	<?php endif; ?>	

</div>