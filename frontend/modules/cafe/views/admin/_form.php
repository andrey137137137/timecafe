<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
  use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $model frontend\modules\cafe\models\Cafe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cafe-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'max_person')->textInput() ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>

    <?= $form->field($model, 'tps_code')->textInput() ?>

    <?= $form->field($model, 'tvq_code')->textInput() ?>

    <?= $form->field($model, 'tps_value')->textInput() ?>

    <?= $form->field($model, 'tvq_value')->textInput() ?>


    <?php if(Yii::$app->user->can('AllFranchisee')) {
      echo $form->field($model, 'franchisee')->dropDownList(Yii::$app->params['franchisee']);
    }?>

    <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>

