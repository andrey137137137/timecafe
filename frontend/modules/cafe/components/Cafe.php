<?php

namespace frontend\modules\cafe\components;

use frontend\modules\users\models\Users;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use frontend\modules\cafe\models\Cafe as CafeModule;

class Cafe extends Component
{

  private $cafe=false;
  private $iCan = [];
  private $usersList = false;

  public function init()
  {
    parent::init();
    $cafe_id = Yii::$app->session->get('cafe_id',false);
    if(!$cafe_id)return;

    $cache = Yii::$app->cache;

    $data = $cache->getOrSet("cafe_params_".$cafe_id, function () use ($cafe_id)  {
      $data['cafe']=CafeModule::findOne(['id'=>$cafe_id]);

      if(!$data['cafe'])return false;

      $data['params']=$data['cafe']->getParam()->one()->toArray();
      $data['iCan'] = isset(Yii::$app->params['iCan']) ? Yii::$app->params['iCan'] : [];

      return $data;
    });

    $this->cafe=$data['cafe'];

    if($data['cafe']) {
      Yii::$app->timeZone = $data['params']['time_zone'];
      $this->iCan = $data['iCan'];
    }else{
      $this->iCan=[];
    }
  }

  public function can($code){
    if(!isset($this->iCan[$code]))return false;
    return $this->iCan[$code];
  }

  public function getId(){
    return $this->cafe?$this->cafe->id:null;
  }
  public function getName(){
    return $this->cafe?$this->cafe->name:null;
  }

  public function getUsersList(){
    if(!$this->cafe)return array();

    if(!$this->usersList) {
      $this->usersList = Users::find()
          ->where(['franchisee' => $this->cafe->franchisee])
          ->asArray()
          ->all();
    };

    return $this->usersList;
  }
}