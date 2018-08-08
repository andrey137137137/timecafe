<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\visits\models\VisitorLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-log-form">

  <?php $form = ActiveForm::begin(); ?>

  <ul>
  <?php   foreach ($columns as $column){
    ?>
    <li>
      <label>
        <input <?= in_array($column,$sel_column)?'checked':'';?> type="checkbox" name="column[]" value="<?=$column;?>">
        <span><?=$model->getAttributeLabel($column);?></span>
      </label>
    </li>
  <?php   } ?>
  </ul>

  <?php ActiveForm::end(); ?>

</div>
