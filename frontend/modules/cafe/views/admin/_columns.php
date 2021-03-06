<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\widget\NumberRangerWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

return [
    [
      'class' => 'kartik\grid\SerialColumn',
      'width' => '30px',
    ],
    'id',
    'name',
    [
      'attribute' => 'max_person',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'max_person',
      ])
    ],
    'address',
    [
      'attribute'=>'franchisee',
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
        [
          ''=>Yii::t('app', 'ALL')
        ],
        Yii::$app->params['franchisee']
      ),
      'value' => function ($model, $key, $index, $column) {
        $param= Yii::$app->params['franchisee'];
        return isset($param[$model->franchisee])?$param[$model->franchisee]:"-";
      },
    ],
    [
      'attribute'=>'currency',
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
        [
          ''=>Yii::t('app', 'ALL')
        ],
        Yii::$app->params['currency']
      ),
      'value' => function ($model, $key, $index, $column) {
        $param= Yii::$app->params['currency'];
        return isset($param[$model->currency])?$param[$model->currency]:"-";
      },
    ],
    [
      'attribute' => 'logo',
      'content' => function ($model, $key, $index, $widget) {
        $image = $model->logo ? 'logos/' . $model->logo : 'logo_black_login.png';
        // return Html::decode("<div class='img-wrap><img class='img-wrap__img src='/img/{$image}' alt='{$model->name}'></div>");
        return Html::img(
          '/img/' . $image,
          ['class' => 'img_wrap__img frame__img', 'alt' => $model->name]
        );
      }
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

