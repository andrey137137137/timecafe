<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset; 
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\tarifs\models\TarifsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tarifs');
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="tarifs-index">
    <div id="ajaxCrudDatatable">
        <?=GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            //'floatHeader'=>true,
            //'floatHeaderOptions'=>['scrollingTop'=>'50'],
            'pjax'=>true,
            'columns' => $columns,
            'toolbar'=> [
                ['content'=>
                    Html::a('<i class="glyphicon glyphicon-th-list"></i>', ['columns'],
                    ['role'=>'modal-remote','title'=> Yii::t('app', 'Columns visibled'),'class'=>'btn btn-default']).

                    ($canCreate?Html::a('<i class="glyphicon glyphicon-plus"></i>', ['create'],
                    ['role'=>'modal-remote','title'=> Yii::t('app', 'Create new Tarifs'),'class'=>'btn btn-default']):'').

                    Html::a('<i class="glyphicon glyphicon-repeat"></i>', [''],
                    ['data-pjax'=>1, 'class'=>'btn btn-default', 'title'=>Yii::t('app', 'Reset Grid')]).

                    '{toggleData}'.
                    '{export}'
                ],
            ],          
            'striped' => true,
            'condensed' => true,
            'panel' => [
                'type' => 'default',
                'heading' => '<i class="glyphicon glyphicon-list"></i> '.Yii::t('app', 'Cafes listing'),
                'before'=>'<em>'.Yii::t('app', '* Resize table columns just like a spreadsheet by dragging the column edges.').'</em>',
                'after'=>$afterTable,
            ]
        ])?>
    </div>
</div>
<?php Modal::begin([
    "id"=>"ajaxCrudModal",
    "footer"=>"",// always need it for jquery plugin
])?>
<?php Modal::end(); ?>
