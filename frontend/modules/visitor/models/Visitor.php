<?php

namespace frontend\modules\visitor\models;

use Yii;

/**
 * This is the model class for table "visitor".
 *
 * @property int $id
 * @property string $f_name
 * @property string $l_name
 * @property string $code
 * @property string $email
 * @property string $phone
 * @property int $create
 * @property string $notice
 * @property int $lg
 *
 * @property PollsAns[] $pollsAns
 */
class Visitor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'visitor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['f_name', 'l_name', 'code', 'email', 'phone', 'create'], 'required'],
            [['f_name', 'l_name', 'code', 'email', 'phone', 'notice'], 'string'],
            [['create', 'lg'], 'integer'],
            [['f_name', 'l_name', 'code', 'email', 'phone','lg'], 'trim'],

            [[ 'email'], 'email'],
            [[ 'code','email','phone'], 'unique']
        ];
    }

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
            'create' => Yii::t('app', 'create'),
            'notice' => Yii::t('app', 'Notice'),
            'lg' => Yii::t('app', 'lg'),
        ];
    }

    public function beforeValidate()
    {
      $old_attr=$this->oldAttributes;

      if($this->code=='' || !$this->code){
        $this->code=isset($old_attr['code'])&&strlen($old_attr)>3?
            $old_attr['code']:
            Visitor::newCode();
      }
      if(($this->email=='' || !$this->email)&&isset($old_attr['email'])){
        $this->email=$old_attr['email'];
      }
      if(($this->phone=='' || !$this->phone)&&isset($old_attr['phone'])){
        $this->phone=$old_attr['phone'];
      }

      return parent::beforeValidate(); // TODO: Change the autogenerated stub
    }

    static function newCode(){
      $v=Visitor::find()
          ->where(['like','code','ANTI%',false])
          ->orderBy([
              'CONVERT(SUBSTRING(`code`, 5), UNSIGNED INTEGER) DESC' => ''
          ])
          ->one();
      $code=(int)preg_replace("/[^0-9]/", '', $v->code);
      $code++;
      return 'ANTI'.$code;
    }

  /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollsAns()
    {
        return $this->hasMany(PollsAns::className(), ['visitor_id' => 'id']);
    }
}
