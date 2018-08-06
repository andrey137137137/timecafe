<?php

namespace frontend\modules\tarifs\models;

use Yii;
use frontend\modules\cafe\models\Cafe;
/**
 * This is the model class for table "tarifs".
 *
 * @property int $id
 * @property int $cafe_id
 * @property double $min_sum
 * @property double $max_sum
 * @property double $first_hour
 * @property double $next_hour
 * @property double $start_visit
 * @property int $active
 *
 * @property Cafe $cafe
 */
class Tarifs extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tarifs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cafe_id', 'min_sum', 'max_sum', 'first_hour', 'next_hour'], 'required'],
            [['cafe_id', 'active'], 'integer'],
            [['min_sum', 'max_sum', 'first_hour', 'next_hour', 'start_visit'], 'number'],
            [['cafe_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cafe::className(), 'targetAttribute' => ['cafe_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'cafe_id' => Yii::t('app', 'Cafe ID'),
            'min_sum' => Yii::t('app', 'Min Sum'),
            'max_sum' => Yii::t('app', 'Max Sum'),
            'first_hour' => Yii::t('app', 'First Hour'),
            'next_hour' => Yii::t('app', 'Next Hour'),
            'start_visit' => Yii::t('app', 'Start Visit'),
            'active' => Yii::t('app', 'Active'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCafe()
    {
        return $this->hasOne(Cafe::className(), ['id' => 'cafe_id']);
    }
}
