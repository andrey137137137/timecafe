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
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
          [
              '0'=>Yii::t('app',"ALL"),
              '-1'=>Yii::t('app',"nobody"),
          ],
          ArrayHelper::map((array)Yii::$app->cafe->getUsersList(), 'id', 'name')
      ),
      'value' => function ($model, $key, $index, $column) {
        $user=$model->user;
        return $user?$user->name:'-';
      },
    ],
    [
      'attribute' => 'visitor_id',
      'value' => function ($model, $key, $index, $column) {
        if(!$model->visitor_id)return Yii::t('app', 'Anonymous');
        $visitor = $model->visitor;
        return $visitor->f_name.' '.$visitor->l_name;
      },
    ],
    [
      'attribute' => 'type',
      'filterType' => GridView::FILTER_SELECT2,
      'format' => 'raw',
      'filter'=> ArrayHelper::merge(
          [
              '-1'=>Yii::t('app',"ALL"),
          ],
          \frontend\modules\visits\models\VisitorLog::typeList()
      ),
      'value' => function ($model, $key, $index, $column) {
        return \frontend\modules\visits\models\VisitorLog::typeList($model->type);
      }
    ],
    [
      'attribute' => 'add_time',
      'filterType' => GridView::FILTER_DATE_RANGE,
      'filterWidgetOptions' =>Yii::$app->params['datetime_option'],
      'value'=> function ($model, $key, $index, $column) {
        if(!$model->add_time)return '-';
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
        if(!$model->finish_time)return '-';
        $datetime=strtotime($model->finish_time);
        return date(Yii::$app->params['lang']['datetime'], $datetime);
      },
    ],
    [
        'attribute' => 'sum',
        'filter'=>NumberRangerWidget::widget([
            'model'=>$searchModel,
            'attribute'=>'sum',
        ]),
        'value'=>function ($model, $key, $index, $column) {
          return number_format($model->sum,  2,'.',' ').' '.Yii::$app->cafe->getCurrency();
        }
    ],
    [
        'attribute' => 'vat',
        'filter'=>false,
        'enableSorting' => false,
        'format' => 'raw',
        'value'=>function ($model, $key, $index, $column) {
          $out=array();
          $vat_list=$model->vat;
          if(!is_array($vat_list))return "-";
          foreach ($vat_list as $vat){
            $out[]='<nobr>'.$vat['name'].' ('.$vat['value'].'%): '.number_format($vat['vat'],  2,'.',' ').' '.Yii::$app->cafe->getCurrency().'</nobr>';
          }
          return implode('<br>',$out);
        }
    ],
    [
      'attribute' => 'cost',
      'filter'=>NumberRangerWidget::widget([
        'model'=>$searchModel,
        'attribute'=>'cost',
      ]),
      'value'=>function ($model, $key, $index, $column) {
        return number_format($model->cost,  2,'.',' ').' '.Yii::$app->cafe->getCurrency();
      }
    ],
    'notice',
    [
      'attribute' => 'pay_state',
        'filterType' => GridView::FILTER_SELECT2,
        'format' => 'raw',
        'filter'=> ArrayHelper::merge(
            [
                '-2'=>Yii::t('app',"ALL"),
            ],
            \frontend\modules\visits\models\VisitorLog::payStatusList()
        ),
        'value' => function ($model, $key, $index, $column) {
          return \frontend\modules\visits\models\VisitorLog::payStatusList($model->pay_state);
        }
    ],
    [
      'attribute' => 'pause',
        'value' => function ($model, $key, $index, $column) {
          $time=$model->pause;
          if(!$time || $time<0)return "-";

          $s= $time % 60;
          $time=round(($time-$s)/60);
          $m= ($time) % 60;
          $h=round(($time-$m)/60);

          if($m<10)$m='0'.$m;
          if($s<10)$s='0'.$s;
          return $h.':'.$m;
        }
    ],
 /*   [
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
    ],*/
    [
        'attribute' => 'visit_cnt',
        'filter'=>false,
        'enableSorting' => false,
    ],
   /* 'pay_man',
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
    'certificate_number',*/
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

