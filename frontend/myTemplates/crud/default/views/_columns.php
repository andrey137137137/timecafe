<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$modelClass = StringHelper::basename($generator->modelClass);
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();
$actionParams = $generator->generateActionParams();

echo "<?php\n";

?>
use yii\helpers\Url;
use kartik\grid\GridView;

return [
    [
      'class' => 'kartik\grid\CheckboxColumn',
      'width' => '20px',
    ],
    [
      'class' => 'kartik\grid\SerialColumn',
      'width' => '30px',
    ],
<?php
    $count = 0;
    foreach ($generator->getColumnNames() as $name) {
      $params='    '.getColumnParams($name);
      if(strpos($name,'pass')!==false || strpos($name,'hash')!==false|| strpos($name,'sess')!==false)continue;
      if ($name=='id'||$name=='created_at'||$name=='updated_at'||strpos($name,'data')!==false){
        echo $params."\n";
      } else if (++$count < 80) {
        echo $params."\n";
      } else {
        echo '/*'.$params.'*/'."\n";
      }
    }
?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template'=>$actions,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'<?=substr($actionParams,1)?>'=>$key]);
        },
        'updateOptions'=>[
            'role'=>'modal-remote',
            'title'=>'',
            'label'=>"<div class=\"btn btn-info btn-xs admin\"><i class=\"fa fa-pencil\"></i> ".<?= $generator->generateString('Edit data');?>."</div>",
        ],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'',
                          'label'=>"<div class=\"btn btn-warning btn-xs admin\"><i class=\"fa fa-stop\"></i> ".<?= $generator->generateString('Delete');?>."</div>",
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=><?= $generator->generateString('Are you sure?');?>,
                          'data-confirm-message'=><?= $generator->generateString('Are you sure want to delete this item');?>,]
    ],

];

<?php

function getColumnParams($name){
  if(strpos($name,'color')!==false){
    return '[
      \'attribute\' => \''.$name.'\',
      \'value\' => function ($model, $key, $index, $widget) {
        return "<span class=\'badge\' style=\'background-color: {$model->'.$name.'}\'> </span>  <code>" . $model->'.$name.' . \'</code>\';
      },
      \'width\' => \'120px\',
      \'filterType\' => GridView::FILTER_COLOR,
      \'filterWidgetOptions\' => [
        \'showDefaultPalette\' => false,
        \'pluginOptions\' => \Yii::$app->params["colorPluginOptions"],
      ],
      \'vAlign\' => \'middle\',
      \'format\' => \'raw\',
      \'noWrap\' => true
    ],';
  }

  return '\''.$name.'\',';
}