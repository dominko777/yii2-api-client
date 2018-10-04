<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'Создать фильм';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="site-login">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'create-movie-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Создать', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <div id="response">
        <h3>Ответ:</h3>
        <div id="response-body">
        </div>
    </div>

<?php
$afterLoginUrl = Url::base();
$createMovieUrl = Yii::$app->params['apiServerUrl'] . 'movie/create';

$script = <<< JS
    
    $('form#create-movie-form').on('beforeSubmit', function (e) {
         e.preventDefault();
         e.stopImmediatePropagation(); 
         var form = $(this);
         if (form.find('.has-error').length) {
              return false;
         }
         var name = $('#createmovieform-name').val();
         var user_id = $('#createmovieform-user_id').val();
         $.ajax({
              url: '$createMovieUrl',
              type: 'post',
              cache: false,
              data: {name: name, user_id: user_id},
              success: function (response) {  console.log(response);
                   if (response.status == 'success') { 
                       alert(response);
                      // window.location.href = '$afterLoginUrl';
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
