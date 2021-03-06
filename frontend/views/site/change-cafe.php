<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app', 'Change cafe');
$isModal=(isset($isModal) && $isModal);
$cafe_change=(isset($cafe_change)?$cafe_change:0);
?>

<?php $form = ActiveForm::begin([
    'id' => $isModal?"cafe_change":'login-form',
    'layout' => 'horizontal',
    'fieldConfig'=>[
      'horizontalCssClasses'=>[
        'wrapper'=>'input-group',
      ]
    ],
    'options' => [
      'class' => $isModal?"cafe_change":'login-form has-science-blue',
      'role' => 'form'
    ],
    ]); ?>

    <div class="text-center margin-off">
      <h2><img src="/img/logo_black_login.png" alt="logo" style="width:140px;"><!--</br>Anticafe Montreal--></h2>
      <!--<h1 class="text-light">Login to service<h1>-->
      <hr class="has-science-blue">
    </div>

    <div class="form-control-addon-fill pad">
      <div class="input-group">
        <span class="input-group-addon fg-white"><i class="fa fa-home"></i></span>
        <select class="form-control" name="cafe">
          <?php foreach ($cafe_list as $cafe){ ?>
            <option value="<?=$cafe['id'];?>" <?=($cafe['id']==$cafe_change)?"selected":"";?>><?=$cafe['name'];?></option>
          <?php } ?>
        </select>
      </div>
    </div>


<?php if(!$isModal ){?>
    <div class="form-group">
        <?= Html::submitButton(\Yii::t('app', 'Enter'), ['class' => 'btn btn-science-blue form-control', 'name' => 'login-button']) ?>
    </div>
<?php }?>

<?php ActiveForm::end(); ?>

