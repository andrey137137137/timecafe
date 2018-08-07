<?php

namespace frontend\modules\visits\model;

use Yii;
use yii\base\Model;

class StartVisit extends \yii\db\ActiveRecord
{

  public $f_name;
  public $l_name;
  public $id;
  public $lg;
  public $code;
  public $phone;
  public $email;
  public $type=false;

  //таблица задана что б проверить уникальность полей
  public static function tableName()
  {
    return 'visitor';
  }

//https://github.com/twitter/typeahead.js/blob/master/doc/jquery_typeahead.md#datasets
  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'id' => Yii::t('app', 'ID'),
      'f_name' => Yii::t('app', 'F Name'),
      'l_name' => Yii::t('app', 'L Name'),
      'code' => Yii::t('app', 'Code'),
      'email' => Yii::t('app', 'Email'),
      'phone' => Yii::t('app', 'Phone'),
      'lg' => Yii::t('app', 'lg'),
      'type' => Yii::t('app', 'visitor type'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
        [['f_name', 'l_name', 'code', 'email', 'phone','lg'], 'trim'],
        [['type'], 'required'],
        [['f_name', 'l_name', 'code', 'email', 'phone','lg'], 'string'],
        [[ 'id','type'], 'integer'],
        [[ 'email'], 'email'],
        [[ 'code','email','phone'], 'unique'],
        [['f_name'],"required",'when' => function($model) {
          return $model->type > 0;
        }]
        //[[ 'email'], 'email'],

    ];
  }
}
