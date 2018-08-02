<?php

namespace common\components\widget;

use yii\base\Widget;
use yii\helpers\Html;
use kartik\slider\Slider;

class NumberRangerWidget extends Widget
{
  public $model;
  public $attribute;
  public $className;

  public function init()
  {
    parent::init();
    if ($this->className === null) {
      $this->className = explode('/',str_replace('\\','/',$this->model->className()));
      $this->className=$this->className[count($this->className)-1];
    }
  }

  private function genName($name,$isModel=false){
    if($isModel){
      return $this->className.'['.$this->attribute.'_'.$name.']';
    }
    return $this->className.'_'.$this->attribute.'_'.$name;
  }

  private function genValue($name,$default=""){
    $name=$this->attribute.'_'.$name;
    return (isset($this->model->$name)?$this->model->$name:$default);
  }

  public function run()
  {
    $name=$this->id;
    $params=$this->model->getSlideParams($this->attribute);
    $js='
    '.$name.'=$( "#'.$name.'" ).slider({
      range: true,
      values: [ '.$this->genValue('from',$params['min']).', '.$this->genValue('to',$params['max']).' ],
      min: '.$params['min'].',
      max: '.$params['max'].',
      step: '.$params['step'].',
      slide: function( event, ui ) {
        $( "[name=\"'.$this->genName('from',true).'\"]" ).val( ui.values[ 0 ]);
        $( "[name=\"'.$this->genName('to',true).'\"]" ).val( ui.values[ 1 ]);
      },
      stop: function( event, ui ) {$( "[name=\"'.$this->genName('from',true).'\"]" ).change()}
    });
    
    
    ';
    $html='
      <div class="range_filter-wrap stopEvent">
        <input for="'.$name.'" type="text" class="onlyFloat range_filter-input showControl" name="'.$this->genName('from',true).'" value="'.$this->genValue('from').'">
        -
        <input for="'.$name.'" type="text" class="onlyFloat range_filter-input showControl" name="'.$this->genName('to',true).'" value="'.$this->genValue('to').'">
        <div class="temp_show" id="'.$name.'-wrap">
          <div id="'.$name.'"></div>
        </div>
      </div>';
    return "$html<script>$js</script>";
  }
}