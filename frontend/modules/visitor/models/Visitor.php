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
            [['f_name', 'l_name', 'code', 'email', 'phone', 'create', 'notice'], 'required'],
            [['f_name', 'l_name', 'code', 'email', 'phone', 'notice'], 'string'],
            [['create', 'lg'], 'integer'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPollsAns()
    {
        return $this->hasMany(PollsAns::className(), ['visitor_id' => 'id']);
    }
}
