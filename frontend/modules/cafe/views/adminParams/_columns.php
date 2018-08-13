<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\widget\NumberRangerWidget;
use yii\helpers\ArrayHelper;
use frontend\modules\users\models\Users;

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
    'vat_list',
    [
        'attribute'=>'time_zone',
        'filterType' => GridView::FILTER_SELECT2,
        'format' => 'raw',
        'filter'=> ArrayHelper::merge(
            [
                ''=>Yii::t('app', 'ALL')
            ],
            Yii::$app->params['timeZone']
        ),
        'value' => function ($model, $key, $index, $column) {
          $param= Yii::$app->params['timeZone'];
          return isset($param[$model->timeZone])?$param[$model->timeZone]:"-";
        },
    ],
    [
      'class' => 'kartik\grid\ActionColumn',
      'dropdown' => false,
      'template'=>$actions,
      'vAlign'=>'middle',
      'urlCreator' => function($action, $model, $key, $index) {
        return Url::to([$action,'id'=>$key]);
      },
      'updateOptions'=>[
        'role'=>'modal-remote',
        'title'=>'',
        'label'=>"<div class=\"btn btn-info btn-xs admin\"><i class=\"fa fa-pencil\"></i> ".Yii::t('app', 'Edit data')."</div>",
      ],
      'deleteOptions'=>['role'=>'modal-remote','title'=>'',
        'label'=>"<div class=\"btn btn-warning btn-xs admin\"><i class=\"fa fa-stop\"></i> ".Yii::t('app', 'Delete')."</div>",
        'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
        'data-request-method'=>'post',
        'data-toggle'=>'tooltip',
        'data-confirm-title'=>Yii::t('app', 'Are you sure?'),
        'data-confirm-message'=>Yii::t('app', 'Are you sure want to delete this item'),]
    ],

];

