<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;
?>


<section id="site-register" class="site-register">
<div class="container">
    <div class="row">

        <h1 class="col-lg-11"><?= Html::encode($this->title) ?></h1>
        <p class="col-lg-11 text-center">Створіть свій аккаунт просто зараз</p>


        <?php if (Yii::$app->session->hasFlash('contactFormSubmitted')): ?>
        Зарегистрировался
        <?php endif;?>


        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'options'=>['class'=>'col-lg-5 col-lg-offset-3 col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-9 col-md-9 col-sm-8 col-sx-12\">{input}</div>\n<div class=\"col-lg-9 col-lg-offset-3 col-md-9 col-md-offset-3 col-sm-8 col-sm-offset-4 col-sx-12 col-sx-offset-0\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-3 col-md-3 col-sm-4 col-sx-12 control-label'],
            ],
        ]); ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'password2')->passwordInput()->label('Confirm password') ?>
        <?= $form->field($model, 'agree')->checkbox([
            'template' => "<div class=\"col-lg-12 col-xs-12 text-center\">{input} 
<span id=\"check_btn\"></span>{label}</div>\n<div class=\"col-lg-12 col-xs-12\">{error}</div>",
        ])->label("Agree with service <a class=\"control-label-bold active\" href=''>Terms & Conditions</a>",['class' => 'control-label-bold']) ?>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                $("#check_btn").on('click',function (e) {
                    e.preventDefault();
                    if ($(this).siblings('input').prop("checked") == true) {
                        $(this).siblings('input').prop('checked', false);
                        $(this).siblings('input').attr('checked', false);
                    } else {
                        $(this).siblings('input').prop('checked', true);
                        $(this).siblings('input').attr('checked', true);
                    }
                });
            });
        </script>


<!--        <script src='https://www.google.com/recaptcha/api.js'></script>-->
<!--        <div class="g-recaptcha" data-sitekey="6Ld7Rj4UAAAAAOtdDPDK33WhkENWR8pjxhDTHtWl"></div>-->


        <?php if(isset($_GET['r']) && mb_strlen($_GET['r'])):?>
            <input type = "hidden" name = 'ref' value = '<?=(string)$_GET['r']?>'>
        <?php endif;?>
        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11 text-center">
                <?= Html::submitButton('Register', ['class' => 'main-btn', 'name' => 'register-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
</section>