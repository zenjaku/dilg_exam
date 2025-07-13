<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="d-flex justify-content-center align-items-center">
    <div class="card w-50">
        <?=
            Html::tag(
                'div',
                Html::encode($this->title),
                ['class' => 'card-header fw-bold fs-3']
            )
            ?>
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
        <?= Html::tag(
            'div',
            $form->field($model, 'username')->textInput(['autofocus' => true]) .
            $form->field($model, 'email') .
            $form->field($model, 'password')->passwordInput() .
            $form->field($model, 'confirm_password')->passwordInput() .
            Html::tag(
                'p',
                "Already have an account yet? Click " . Html::a('here', ['login']) . " to login."
            ),
            ['class' => 'card-body']
        ) ?>
        <?= Html::tag(
            'div',
            Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']),
            ['class' => 'card-footer']
        ) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>