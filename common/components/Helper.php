<?php

namespace common\components;

use frontend\modules\tarifs\models\Tarifs;
use frontend\modules\users\models\Users;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use frontend\modules\cafe\models\Cafe as CafeModule;

class Helper extends Component
{

  public function echo_time($time) {
    if(!$time || $time<0)$time=0;

    $s= $time % 60;
    $time=round(($time-$s)/60);
    $m= ($time) % 60;
    $h=round(($time-$m)/60);

    if($m<10)$m='0'.$m;
    if($s<10)$s='0'.$s;
    return $h.':'.$m;
  }
}