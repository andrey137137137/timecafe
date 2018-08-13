<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use frontend\modules\users\models\Users;

/* @var $this yii\web\View */
/* @var $model frontend\modules\cafe\models\CafeParams */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cafe-params-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vat_list')->textInput(['maxlength' => true]) ?>

  <?php if(Yii::$app->user->can('AllChange')) {
    echo $form->field($model, 'time_zone')->dropDownList(Yii::$app->params['timeZone']);
  }?>


  <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>


