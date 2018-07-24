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
      if ($name=='id'||$name=='created_at'||$name=='updated_at'||strpos($name,'data')!==0){
        echo "     [\n";
        echo "         'class'=>'\kartik\grid\DataColumn',\n";
        echo "         'attribute'=>'" . $name . "',\n";
        echo "     ],\n";
      } else if (++$count < 8) {
        echo "    [\n";
        echo "        'class'=>'\kartik\grid\DataColumn',\n";
        echo "        'attribute'=>'" . $name . "',\n";
        echo "    ],\n";
      } else if (strpos($name,'time')!==0) {
        echo "    [\n";
        echo "        'class'=>'\kartik\grid\DataColumn',\n";
        echo "        'attribute'=>'" . $name . "',\n";
        echo "    ],\n";
      } else {
        echo "    // [\n";
        echo "        // 'class'=>'\kartik\grid\DataColumn',\n";
        echo "        // 'attribute'=>'" . $name . "',\n";
        echo "    // ],\n";
      }
    }
    ?>
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template'=>'{update}{delete}',
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'<?=substr($actionParams,1)?>'=>$key]);
        },
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   