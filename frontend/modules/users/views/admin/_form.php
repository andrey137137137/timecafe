<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
  use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $model frontend\modules\users\models\Users */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="users-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'new_password')->textInput() ?>

    <?= $form->field($model, 'role')->textInput() ?>

    <?= $form->field($model, 'state')->dropDownList([
        0=>Yii::t('app', 'Active'),
        1=>Yii::t('app', 'Blocked'),
    ]) ?>

    <?= $form->field($model, 'email')->textInput() ?>

    <?= $form->field($model, 'color')->widget(ColorInput::classname(), [
        'options' => ['placeholder' => 'Select color ...'],
        'showDefaultPalette' => false,
        'pluginOptions' => \Yii::$app->params["colorPluginOptions"],
    ]); ?>

    <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>


