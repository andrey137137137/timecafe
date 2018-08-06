<?php

namespace frontend\modules\visits\model;

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
 * @property string $create
 * @property string $notice
 * @property string $lg
 *
 * @property PollsAns[] $pollsAns
 */
class VisitorLog extends \yii\db\ActiveRecord
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
            [['f_name', 'l_name', 'code', 'email', 'phone', 'notice'], 'required'],
            [['f_name', 'l_name', 'code', 'email', 'phone', 'notice'], 'string'],
            [['create'], 'safe'],
            [['lg'], 'string', 'max' => 6],
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
            'create' => Yii::t('app', 'Create'),
            'notice' => Yii::t('app', 'Notice'),
            'lg' => Yii::t('app', 'Lg'),
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
