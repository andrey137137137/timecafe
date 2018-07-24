<?php

namespace frontend\modules\users\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $user
 * @property string $pass
 * @property string $last_sess
 * @property int $role
 * @property int $state
 * @property string $cafe
 * @property string $email
 * @property string $color
 *
 * @property Polls[] $polls
 * @property UserTimetable[] $userTimetables
 */
class Users extends \yii\db\ActiveRecord
{

  public $new_password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'pass', 'last_sess', 'cafe_id', 'email', 'color'], 'string'],
            [['role', 'state'], 'integer'],
            ['new_password', 'trim'],
            [['new_password'], 'string', 'max' => 60],
            [['new_password'], 'string', 'min' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user' => Yii::t('app', 'User'),
            'pass' => Yii::t('app', 'Pass'),
            'last_sess' => Yii::t('app', 'Last Sess'),
            'role' => Yii::t('app', 'Role'),
            'state' => Yii::t('app', 'State'),
            'cafe' => Yii::t('app', 'Cafe'),
            'email' => Yii::t('app', 'Email'),
            'color' => Yii::t('app', 'Color'),
        ];
    }

  public function beforeValidate()
  {
    if (!parent::beforeValidate()) {
      return false;
    }

    if ($this->isNewRecord) {
      if (!$this->name || strlen($this->name) == 0) {
        $this->name = explode('@', $this->email);
        $this->name = $this->name[0];
      }
      /*$this->reg_ip = $_SERVER["REMOTE_ADDR"];
      $this->referrer_id = (int)Yii::$app->session->get('referrer_id');
      $this->added = date('Y-m-d H:i:s');
      if (!isset($this->auth_key)) {
        $this->auth_key = '';
      }*/
    }
    if ($this->new_password) {
      $this->setPassword($this->new_password);
    }
    return true;
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password)
  {
    $this->new_password = $password;
    $this->pass = Yii::$app->security->generatePasswordHash($password);
  }

  /**
   * Действия, выполняющиеся после авторизации.
   * Сохранение IP адреса и даты авторизации.
   *
   * Для активации текущего обновления необходимо
   * повесить текущую функцию на событие 'on afterLogin'
   * компонента user в конфигурационном файле.
   * @param $id - ID пользователя
   */
  public static function afterLogin($id)
  {
    /*if (
        !Yii::$app->session->get('admin_id') ||
        Yii::$app->session->get('admin_id') != Yii::$app->user->id
    ) {
      self::getDb()->createCommand()->update(self::tableName(), [
          'last_ip' => $_SERVER["REMOTE_ADDR"],
          'last_login' => date('Y-m-d H:i:s'),
      ], ['uid' => $id])->execute();
    }*/
  }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPolls()
    {
        return $this->hasMany(Polls::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserTimetables()
    {
        return $this->hasMany(UserTimetable::className(), ['user_id' => 'id']);
    }
}