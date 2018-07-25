<?php
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
    'id',
    'name',
    'role',
    'state',
    'cafe_id',
    'email',
    [
      'attribute' => 'color',
      'value' => function ($model, $key, $index, $widget) {
        return "<span class='badge' style='background-color: {$model->color}'> </span>  <code>" . $model->color . '</code>';
      },
      'width' => '120px',
      'filterType' => GridView::FILTER_COLOR,
      'filterWidgetOptions' => [
        'showDefaultPalette' => false,
        'pluginOptions' => \Yii::$app->params["colorPluginOptions"],
      ],
      'vAlign' => 'middle',
      'format' => 'raw',
      'noWrap' => true
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'template'=>$actions,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
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

