<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use frontend\modules\users\models\Users;

/* @var $this yii\web\View */
/* @var $model frontend\modules\tarifs\models\Tarifs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tarifs-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cafe_id')->dropDownList(\yii\helpers\ArrayHelper::map((array)Users::getCafesList(), 'id', 'name')) ?>

    <?= $form->field($model, 'min_sum')->textInput() ?>

    <?= $form->field($model, 'max_sum')->textInput() ?>

    <?= $form->field($model, 'first_hour')->textInput() ?>

    <?= $form->field($model, 'next_hour')->textInput() ?>

    <?= $form->field($model, 'start_visit')->textInput() ?>


    <?= $form->field($model, 'active')->dropDownList([
        0 => Yii::t('app', 'Active'),
        1 => Yii::t('app', 'Blocked'),
    ]) ?>

    <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>


