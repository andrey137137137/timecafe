<?php

use yii\db\Schema;

class m180808_090454_visitorlog_RBAC extends \yii\db\Migration
{

  private $auth;
  public function up()
  {
    //применить миграцию
    $this->auth = \Yii::$app->authManager;
    $role = $this->auth->getRole('root');

    $this->createPermission(
      'VisitorLogView',
      'VisitorLog - просмотр (общая таблица)',
      [$role]
    );

    $this->createPermission(
      'VisitorLogUpdate',
      'VisitorLog  - редактирование',
      [$role]
    );

    $this->createPermission(
      'VisitorLogDelete',
      'VisitorLog - удаление',
      [$role]
    );

    $this->createPermission(
      'VisitorLogCreate',
      'VisitorLog - создание',
      [$role]
    );
  }

  public function down()
  {
    //откат миграции
  }

  private function createPermission($name, $description = '', $roles = [])
  {
    $permit = $this->auth->createPermission($name);
    $permit->description = $description;
    $this->auth->add($permit);
    foreach ($roles as $role) {
      $this->auth->addChild($role, $permit);//Связываем роль и привелегию
    }
  }
}
