<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
  use kartik\color\ColorInput;
  use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model frontend\modules\cafe\models\Cafe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cafe-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'max_person')->textInput() ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>


    <?php if(Yii::$app->user->can('AllFranchisee')) {
      echo $form->field($model, 'franchisee')->dropDownList(Yii::$app->params['franchisee']);
    }?>

    <?php if(Yii::$app->user->can('AllChange')) {
      echo $form->field($model, 'currency')->dropDownList(Yii::$app->params['currency']);
    }?>

    <?php
      // echo $form->field($model, 'image')->fileInput();
      echo $form->field($model, 'image')->widget(
        FileInput::classname(),
        [
          'options' => ['accept' => 'image/*'],
          'pluginOptions' => [
            'initialPreview' => [$url1],
            'initialPreviewAsData' => true,
            // 'initialPreviewConfig' => [
            //     ['caption' => "Moon.jpg", 'size' => 930321, 'width' => "120px", 'key' => 1, 'showRemove' => false,],
            // ],
            'showRemove' => false,
            // 'overwriteInitial' => false,
            // 'maxFileSize' => 100,
            // 'initialCaption' => "The Moon and the Earth"
          ]
        ]
      );
    ?>

    <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>


