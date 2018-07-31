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
 * @property UserCafe[] $userCaves
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
            [['name', 'address', 'tps_code', 'tvq_code'], 'string'],
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
        ];
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
    public function getUserCaves()
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
