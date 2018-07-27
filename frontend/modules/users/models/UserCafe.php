<?php

namespace frontend\modules\users\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "cw_users_social".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $cafe_id
 */

class UserCafe extends ActiveRecord
{


  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'user_cafe';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['user_id', 'cafe_id'], 'required'],
      [['user_id', 'cafe_id'], 'integer'],
    ];
  }

  public function attributeLabels()
  {
    return [
        'id' => Yii::t('app', 'ID'),
        'user_id' => Yii::t('app', 'User'),
        'cafe_id' => Yii::t('app', 'Cafe'),
    ];
  }

}

