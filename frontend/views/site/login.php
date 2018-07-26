<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>

<?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'layout' => 'horizontal',
    'fieldConfig'=>[
      'horizontalCssClasses'=>[
        'wrapper'=>'input-group',
      ]
    ],
    'options' => [
      'class' => 'login-form has-science-blue',
      'role' => 'form'
    ],
    ]); ?>

    <div class="text-center margin-off">
      <h2><img src="img/logo_black_login.png" alt="logo" style="width:140px;"><!--</br>Anticafe Montreal--></h2>
      <!--<h1 class="text-light">Login to service<h1>-->
      <hr class="has-science-blue">
    </div>

    <div class="form-control-addon-fill pad">
      <?= $form->field($model, 'username',[
            'template' => "{beginWrapper}<span class=\"input-group-addon fg-white\"><i class=\"fa fa-user\"></i></span>{input}\n{endWrapper}\n{error}"
        ])->textInput([
            'autofocus' => true,
      ]) ?>
    </div>

    <div class="form-control-addon-fill pad">
        <?= $form->field($model, 'password',[
            'template' => "{beginWrapper}<span class=\"input-group-addon fg-white\"><i class=\"fa fa-lock\"></i></span></i>{input}\n{endWrapper}\n{error}"
        ])->passwordInput([
            'autofocus' => true,
        ]) ?>
    </div>


    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'login'), ['class' => 'btn btn-science-blue form-control', 'name' => 'login-button']) ?>
    </div>

<?php ActiveForm::end(); ?>

<script>
  $(function(){
    var form = $(".login-form");

    form.css({
      opacity: 1,
      "-webkit-transform": "scale(1)",
      "transform": "scale(1)",
      "-webkit-transition": ".5s",
      "transition": ".5s"
    });
  });
</script>
