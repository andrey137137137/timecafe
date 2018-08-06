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
    [
      'attribute'=>'cafe_id',
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
          [
              '0'=>Yii::t('app',"ALL")
          ],
          \yii\helpers\ArrayHelper::map((array)Users::getCafesList(), 'id', 'name')
      ),
      'value' => function ($model, $key, $index, $column) {
        $cafe=$model->cafe;
        return $cafe->name;
      },
    ],
    [
      'attribute' => 'min_sum',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'min_sum',
      ])
    ],
    [
      'attribute' => 'max_sum',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'max_sum',
      ])
    ],
    [
      'attribute' => 'first_hour',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'first_hour',
      ])
    ],
    [
      'attribute' => 'next_hour',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'next_hour',
      ])
    ],
    [
      'attribute' => 'start_visit',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'start_visit',
      ])
    ],
    [
        'attribute' => 'active',
        'filterType' => GridView::FILTER_SELECT2,
        'filterInputOptions' => ['placeholder' => 'Any'],
        'width' => '100px',
        'value' => function ($model, $key, $index, $widget) {
          return $model->active==1?Yii::t('app', 'Blocked'):Yii::t('app', 'Active');
        },
        'filter' => [1=>Yii::t('app', 'Blocked'),0=>Yii::t('app', 'Active')],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
            'options' => ['multiple' => false]
        ],
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

