<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model frontend\modules\visits\models\VisitorLog */

$this->title = Yii::t('app', 'Create Visitor Log');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Visitor Logs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$isAjax=isset($isAjax)?$isAjax:false;
?>
<div class="visitor-log-create">

  <?php if(!$isAjax){?>
  <h1><?= Html::encode($this->title) ?></h1>
  <?php }?>

  <?= $this->render('_form', [
    'model' => $model,
    'isAjax' => $isAjax,
  ]) ?>

</div>
