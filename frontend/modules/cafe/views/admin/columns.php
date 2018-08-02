<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\cafe\models\Cafe */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cafe-form">

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
