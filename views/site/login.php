<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div style="display: none" id="api-errors"><p style="color:#a94442" class="help-block help-block-error "></p></div>

    <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

    <div style="display: none" id="api-success-login"><p style="color:green">Вы успешно авторизовались на сайте под логином:
            <span id="user-login"></span>
        </p>
    </div>

</div>

<?php
$loginUrl = Yii::$app->params['apiServerUrl'] . 'auth/login';

$script = <<< JS
    
    $('form#login-form').on('beforeSubmit', function (e) {
         e.preventDefault();
         e.stopImmediatePropagation(); 
         var form = $(this);
         if (form.find('.has-error').length) {
              return false;
         }
         var username = $('#loginform-username').val();
         var password = $('#loginform-password').val();
         $('#api-errors').hide();
         $.ajax({
              url: '$loginUrl',
              type: 'post',
              cache: false,
              data: {username: username, password: password},
              dataType: "json",
              success: function (response) {  console.log(response);
                  $('#api-errors').hide();
                  if (response.status == 'error') { 
                       $('#api-errors').show();
                       $('#api-errors > p').text(response.message);
                   } 
                   else if (response.status == 'success') { 
                       $('#api-errors').hide();
                       $.cookie("access-token", response.data.token);
                       form.hide();
                       $('#user-login').text(response.data.username);
                       $('#api-success-login').show();
                   } 
              },
               error  : function () 
               {
                   console.log('internal server error');
               }
         });
         return false;
    });

JS;
$this->registerJs($script, View::POS_READY);
