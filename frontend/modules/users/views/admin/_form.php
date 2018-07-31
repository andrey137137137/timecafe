<?php

use frontend\modules\users\models\Users;
use kartik\color\ColorInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\users\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">
  <?php $form = ActiveForm::begin(); ?>


        <?= $form->field($model, 'name')->textInput() ?>

        <?= $form->field($model, 'new_password')->textInput() ?>

        <?= $form->field($model, 'roles')->dropDownList(Users::getRoleList(false)) ?>

        <?= $form->field($model, 'lg')->dropDownList(Yii::$app->params['lg_list']) ?>

        <?= $form->field($model, 'state')->dropDownList([
            0 => Yii::t('app', 'Active'),
            1 => Yii::t('app', 'Blocked'),
        ]) ?>

        <?= $form->field($model, 'email')->textInput() ?>

  <?php if(isset($cafes)){
    foreach($cafes as $k=>$cafe){?>


  <?php }
  }?>

        <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
            'options' => ['placeholder' => 'Select color ...'],
            'showDefaultPalette' => false,
            'pluginOptions' => \Yii::$app->params["colorPluginOptions"],
        ]); ?>
  </div>
  <?php if (!$isAjax) { ?>
    <div class="form-group">
      <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>
  <?php } ?>
  <?php ActiveForm::end(); ?>

</div>


