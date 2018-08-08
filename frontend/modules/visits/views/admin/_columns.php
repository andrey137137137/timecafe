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
      'attribute' => 'user_id',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'user_id',
      ])
    ],
    [
      'attribute' => 'visitor_id',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'visitor_id',
      ])
    ],
    [
      'attribute' => 'type',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'type',
      ])
    ],
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
      'attribute' => 'add_time',
      'filterType' => GridView::FILTER_DATE_RANGE,
      'filterWidgetOptions' =>Yii::$app->params['datetime_option'],
      'value'=> function ($model, $key, $index, $column) {
        $datetime=strtotime($model->add_time);
        return date(Yii::$app->params['lang']['datetime'], $datetime);
      },
    ],
    'comment',
    [
      'attribute' => 'finish_time',
      'filterType' => GridView::FILTER_DATE_RANGE,
      'filterWidgetOptions' =>Yii::$app->params['datetime_option'],
      'value'=> function ($model, $key, $index, $column) {
        $datetime=strtotime($model->finish_time);
        return date(Yii::$app->params['lang']['datetime'], $datetime);
      },
    ],
    [
      'attribute' => 'cost',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'cost',
      ])
    ],
    [
      'attribute' => 'sum',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'sum',
      ])
    ],
    [
      'attribute' => 'tip',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'tip',
      ])
    ],
    [
      'attribute' => 'tps',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'tps',
      ])
    ],
    [
      'attribute' => 'tvq',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'tvq',
      ])
    ],
    'notice',
    [
      'attribute' => 'pay_state',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'pay_state',
      ])
    ],
    [
      'attribute' => 'pause_start',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'pause_start',
      ])
    ],
    [
      'attribute' => 'pause',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'pause',
      ])
    ],
    [
      'attribute' => 'certificate_type',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'certificate_type',
      ])
    ],
    [
      'attribute' => 'certificate_val',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'certificate_val',
      ])
    ],
    'visit_cnt',
    'pay_man',
    [
      'attribute' => 'guest_m',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'guest_m',
      ])
    ],
    [
      'attribute' => 'guest_chi',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'guest_chi',
      ])
    ],
    'cnt_disk',
    [
      'attribute' => 'chi',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'chi',
      ])
    ],
    [
      'attribute' => 'sum_no_cert',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'sum_no_cert',
      ])
    ],
    [
      'attribute' => 'pre_enter',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'pre_enter',
      ])
    ],
    [
      'attribute' => 'kiosk_disc',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'kiosk_disc',
      ])
    ],
    [
      'attribute' => 'terminal_ans',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'terminal_ans',
      ])
    ],
    'certificate_number',
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

