<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\color\ColorInput;
use frontend\modules\users\models\Users;

/* @var $this yii\web\View */
/* @var $model frontend\modules\visits\models\VisitorLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'visitor_id')->textInput() ?>

    <?= $form->field($model, 'type')->textInput() ?>

<?= $form->field($model, 'cafe_id')->dropDownList(\yii\helpers\ArrayHelper::map((array)Users::getCafesList(), 'id', 'name')) ?>

    <?= $form->field($model, 'add_time')->textInput() ?>

    <?= $form->field($model, 'comment')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'finish_time')->textInput() ?>

    <?= $form->field($model, 'cost')->textInput() ?>

    <?= $form->field($model, 'sum')->textInput() ?>

    <?= $form->field($model, 'notice')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pay_state')->textInput() ?>

    <?= $form->field($model, 'pause_start')->textInput() ?>

    <?= $form->field($model, 'pause')->textInput() ?>

    <?= $form->field($model, 'certificate_type')->textInput() ?>

    <?= $form->field($model, 'certificate_val')->textInput() ?>

    <?= $form->field($model, 'visit_cnt')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'pay_man')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'guest_m')->textInput() ?>

    <?= $form->field($model, 'guest_chi')->textInput() ?>

    <?= $form->field($model, 'cnt_disk')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'chi')->textInput() ?>

    <?= $form->field($model, 'sum_no_cert')->textInput() ?>

    <?= $form->field($model, 'pre_enter')->textInput() ?>

    <?= $form->field($model, 'kiosk_disc')->textInput() ?>

    <?= $form->field($model, 'terminal_ans')->textInput() ?>

    <?= $form->field($model, 'certificate_number')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'vat')->textInput(['maxlength' => true]) ?>

    <?php if(!$isAjax){?>
      <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
      </div>
    <?php }?>
    <?php ActiveForm::end(); ?>

</div>


