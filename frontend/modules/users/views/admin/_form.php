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

        <?php if(Yii::$app->user->can('AllFranchisee')) {
          echo $form->field($model, 'franchisee')->dropDownList(Yii::$app->params['franchisee']);
        }?>

        <?= $form->field($model, 'state')->dropDownList([
            0 => Yii::t('app', 'Active'),
            1 => Yii::t('app', 'Blocked'),
        ]) ?>

        <?= $form->field($model, 'email')->textInput() ?>

  <?php if(isset($cafes) && count($cafes)>0){
    $user_cafes=$model->getCafes()->all();
    $cafe_s=[];
    foreach($user_cafes as $cafe){
      $cafe_s[]=$cafe->cafe_id;
    };
    echo "<div class=\"cafe_list\">";
    foreach($cafes as $k=>$cafe){?>
        <div>
          <input name="cafe[]" type="checkbox" <?=(in_array($cafe['id'],$cafe_s)?'checked=checked':'');?> value="<?=$cafe['id'];?>" id="cafe_<?=$k;?>">
          <label for="cafe_<?=$k;?>"><?=$cafe['name'];?></label>
        </div>

  <?php }
    echo "</div>";
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


