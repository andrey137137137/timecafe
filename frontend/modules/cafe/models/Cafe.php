<?php

namespace frontend\modules\cafe\models;

use Yii;

/**
 * This is the model class for table "cafe".
 *
 * @property int $id
 * @property string $name
 * @property int $max_person
 * @property string $address
 * @property string $tps_code
 * @property string $tvq_code
 * @property int $last_task
 * @property double $tps_value
 * @property double $tvq_value
 * @property int $franchisee
 *
 * @property Polls[] $polls
 * @property UserCafe[] $userCafes
 * @property UserTimetable[] $userTimetables
 */
class Cafe extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cafe';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tps_value', 'tvq_value'], 'required'],
            [['name', 'address', 'tps_code', 'tvq_code','currency','timeZone'], 'trim'],
            [['name', 'address', 'tps_code', 'tvq_code','currency','timeZone'], 'string'],
            [['max_person', 'last_task', 'franchisee'], 'integer'],
            [['tps_value', 'tvq_value'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'max_person' => Yii::t('app', 'Max Person'),
            'address' => Yii::t('app', 'Address'),
            'tps_code' => Yii::t('app', 'Tps Code'),
            'tvq_code' => Yii::t('app', 'Tvq Code'),
            'last_task' => Yii::t('app', 'Last Task'),
            'tps_value' => Yii::t('app', 'Tps Value'),
            'tvq_value' => Yii::t('app', 'Tvq Value'),
            'franchisee' => Yii::t('app', 'Franchisee'),
            'currency' => Yii::t('app', 'Currency'),
            'timeZone' => Yii::t('app', 'time Zone'),
        ];
    }


  public function beforeValidate()
  {
    if (!parent::beforeValidate()) {
      return false;
    }

    if ($this->isNewRecord) {
      if (Yii::$app->user->isGuest) {
        $this->franchisee = 1;
      } else if (!Yii::$app->user->can('AllFranchisee')) {
        $this->franchisee = Yii::$app->user->identity->franchisee;
      }
    }

    return true;
  }

  /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Polls::className(), ['cafe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserCafes()
    {
        return $this->hasMany(UserCafe::className(), ['cafe_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTimetables()
    {
        return $this->hasMany(UserTimetable::className(), ['cafe_id' => 'id']);
    }
}
