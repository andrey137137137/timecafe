<?php

use yii\db\Schema;

class m180725_142747_users_RBAC extends \yii\db\Migration
{

  private $auth;
  public function up()
  {
    //применить миграцию
    $this->auth = \Yii::$app->authManager;
    $role = $this->auth->getRole('root');

    $this->createPermission(
      'UsersView',
      'Users - просмотр (общая таблица)',
      [$role]
    );

    $this->createPermission(
      'UsersUpdate',
      'Users  - редактирование',
      [$role]
    );

    $this->createPermission(
      'UsersDelete',
      'Users - блокировка',
      [$role]
    );

    $this->createPermission(
      'UsersCreate',
      'Users - создание',
      [$role]
    );

    $this->createPermission(
        'AllFranchisee',
        'Users - блокировка',
        [$role]
    );

    $this->createPermission(
        'CanChangeCafe',
        'Users - может менять кафе пользователю и в системе',
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
