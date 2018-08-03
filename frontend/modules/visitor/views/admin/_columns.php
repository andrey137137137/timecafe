<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use common\components\widget\NumberRangerWidget;
use yii\helpers\ArrayHelper;

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
    'f_name',
    'l_name',
    'code',
    'email',
    'phone',
    [
      'attribute' => 'create',
      'filterType' => GridView::FILTER_DATE_RANGE,
      'filterWidgetOptions' =>Yii::$app->params['datetime_option'],
      'value'=> function ($model, $key, $index, $column) {
        $datetime=strtotime($model->create);
        return date(Yii::$app->params['lang']['datetime'], $datetime);
      },
    ],
    'notice',
    [
      'attribute'=>'lg',
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
          [
              ''=>Yii::t('app',"ALL")
          ],
          Yii::$app->params['lg_list']
      ),
      'value' => function ($model, $key, $index, $column) {
        $lg= Yii::$app->params['lg_list'];
        return $lg[isset($lg[$model->lg])?$model->lg:Yii::$app->params['defaultLang']];
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

