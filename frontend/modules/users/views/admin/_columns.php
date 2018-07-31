<?php
use yii\helpers\Url;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\BulkButtonWidget;
use yii\helpers\Html;
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
    [
        'attribute'=>'role',
        'filterType' => GridView::FILTER_SELECT2,
        'format' => 'raw',
        'filter'=> Users::getRoleList(),
        'value' => function ($model, $key, $index, $column) {
          return implode(',',$model->getRoleOfUserArray());
        },
    ],[
        'attribute'=>'lg',
        'filterType' => GridView::FILTER_SELECT2,
        'format' => 'raw',
        'filter'=> array_merge(
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
        'attribute' => 'state',
        'filterType' => GridView::FILTER_SELECT2,
        'filterInputOptions' => ['placeholder' => 'Any'],
        'width' => '100px',
        'value' => function ($model, $key, $index, $widget) {
          return $model->state==1?Yii::t('app', 'Blocked'):Yii::t('app', 'Active');
        },
        'filter' => [1=>Yii::t('app', 'Blocked'),0=>Yii::t('app', 'Active')],
        'filterWidgetOptions' => [
            'pluginOptions' => ['allowClear' => true],
            'options' => ['multiple' => false]
        ],
    ],
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
        'buttons'=>[
            'blocked' => function ($url,$model) {
              return $model->state?
                  Html::a('<i class="fa fa-play"></i>&nbsp; '.Yii::t('app','Restore'),
                      ["restore?id=".$model->id] ,
                      [
                          "class"=>"btn btn-science-blue btn-xs",
                          'role'=>'modal-remote',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-confirm-title'=>Yii::t('app', 'Are you sure?'),
                          'data-confirm-message'=>Yii::t('app', 'Are you sure want to restore this user')
                      ])
                  :Html::a('<i class="fa fa-stop"></i>&nbsp; '.Yii::t('app','Blocked'),
                      ["blocked?id=".$model->id] ,
                      [
                          "class"=>"btn btn-warning btn-xs",
                          'role'=>'modal-remote',
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-confirm-title'=>Yii::t('app', 'Are you sure?'),
                          'data-confirm-message'=>Yii::t('app', 'Are you sure want to blocked this user')
                      ]);
            },
        ],
        'updateOptions'=>[
            'role'=>'modal-remote',
            'title'=>"",
            'label'=>"<div class=\"btn btn-info btn-xs admin\"><i class=\"fa fa-pencil\"></i> ".Yii::t('app', 'Edit data')."</div>",
            ],
        'deleteOptions'=>['role'=>'modal-remote',
                          'title'=>Yii::t('app', 'Blocked'),
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>Yii::t('app', 'Are you sure?'),
                          'data-confirm-message'=>Yii::t('app', 'Are you sure want to blocked this user')],
    ],

];

