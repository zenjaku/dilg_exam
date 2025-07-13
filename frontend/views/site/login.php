<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
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
        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
        <?= Html::tag(
            'div',
            $form->field($model, 'username')->textInput(['autofocus' => true]) .
            $form->field($model, 'password')->passwordInput() .
            $form->field($model, 'rememberMe')->checkbox() .
            Html::tag(
                'p',
                "Don't have an account yet? Click " . Html::a('here', ['signup']) . " to signup."
            ),
            ['class' => 'card-body']
        ) ?>
        <?= Html::tag(
            'div',
            Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']),
            ['class' => 'card-footer']
        ) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>