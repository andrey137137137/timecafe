<?php

namespace frontend\modules\cafe\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use frontend\modules\cafe\models\Cafe as CafeModule;

class Cafe extends Component
{

  private $cafe=false;
  private $iCan = [];

  public function init()
  {
    parent::init();
    $cafe_id = Yii::$app->session->get('cafe_id',false);
    if(!$cafe_id)return;

    $this->cafe=CafeModule::findOne(['id'=>$cafe_id]);
    Yii::$app->timeZone=$this->cafe->timeZone;

    $this->iCan= isset(Yii::$app->params['iCan'])?Yii::$app->params['iCan']:[];
  }

  public function can($code){
    if(!isset($this->iCan[$code]))return false;
    return $this->iCan[$code];
  }
}