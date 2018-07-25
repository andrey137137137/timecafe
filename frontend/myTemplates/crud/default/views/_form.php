<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
  use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin(); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
  if(strpos($attribute,'pass')!==false || strpos($attribute,'hash')!==false|| strpos($attribute,'sess'))continue;
    if (in_array($attribute, $safeAttributes)) {
      $input=getColumnInput($attribute);
      echo "    <?= ";
      if($input){
        echo $input;
      }else{
        echo $generator->generateActiveField($attribute);
      }
        echo " ?>\n\n";
    }
} ?>
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Save') ?>, ['class' => 'btn btn-success']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>


<?php
function getColumnInput($name){
  if(strpos($name,'color')!==false){
    return '$form->field($model, \''.$name.'\')->widget(ColorInput::classname(), [
        \'options\' => [\'placeholder\' => \'Select color ...\'],
        \'showDefaultPalette\' => false,
        \'pluginOptions\' => \Yii::$app->params["colorPluginOptions"],
    ]);';
  }

  return false;
}

/*
  - Installing kartik-v/yii2-widget-typeahead (v1.0.1): Downloading (100%)
  - Installing kartik-v/yii2-widget-touchspin (v1.2.1): Downloading (100%)
  - Installing kartik-v/yii2-widget-timepicker (v1.0.3): Downloading (100%)
  - Installing kartik-v/yii2-widget-switchinput (v1.3.1): Downloading (100%)
  - Installing kartik-v/yii2-widget-spinner (v1.0.0): Downloading (100%)
  - Installing kartik-v/yii2-widget-sidenav (v1.0.0): Downloading (100%)
  - Installing kartik-v/yii2-widget-select2 (v2.1.1): Downloading (100%)
  - Installing kartik-v/bootstrap-star-rating (4.0.3): Downloading (100%)
  - Installing kartik-v/yii2-widget-rating (v1.0.3): Downloading (100%)
  - Installing kartik-v/yii2-widget-rangeinput (v1.0.1): Downloading (100%)
  - Installing kartik-v/yii2-widget-growl (v1.1.1): Downloading (100%)
  - Installing kartik-v/bootstrap-fileinput (v4.4.8): Downloading (100%)
  - Installing kartik-v/yii2-widget-fileinput (v1.0.6): Downloading (100%)
  - Installing kartik-v/dependent-dropdown (v1.4.8): Downloading (100%)
  - Installing kartik-v/yii2-widget-depdrop (v1.0.4): Downloading (100%)
  - Installing kartik-v/yii2-widget-datetimepicker (v1.4.4): Downloading (100%)
  - Installing kartik-v/yii2-widget-datepicker (v1.4.4): Downloading (100%)
  - Installing kartik-v/yii2-widget-colorinput (v1.0.3): Downloading (100%)
  - Installing kartik-v/yii2-widget-alert (v1.1.1): Downloading (100%)
  - Installing kartik-v/yii2-widget-affix (v1.0.0): Downloading (100%)
  - Installing kartik-v/yii2-widget-activeform (v1.4.9): Downloading (100%)
  - Installing kartik-v/yii2-widgets (v3.4.0): Downloading (100%)
 */