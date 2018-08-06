<?php

use yii\db\Schema;

class m180806_071254_tarifs_RBAC extends \yii\db\Migration
{

  private $auth;
  public function up()
  {
    //применить миграцию
    $this->auth = \Yii::$app->authManager;
    $role = $this->auth->getRole('root');

    $this->createPermission(
      'TarifsView',
      'Tarifs - просмотр (общая таблица)',
      [$role]
    );

    $this->createPermission(
      'TarifsUpdate',
      'Tarifs  - редактирование',
      [$role]
    );

    $this->createPermission(
      'TarifsDelete',
      'Tarifs - удаление',
      [$role]
    );

    $this->createPermission(
      'TarifsCreate',
      'Tarifs - создание',
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
