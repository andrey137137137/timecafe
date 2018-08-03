<?php

use yii\db\Schema;

class m180803_123055_visitor_RBAC extends \yii\db\Migration
{

  private $auth;
  public function up()
  {
    //применить миграцию
    $this->auth = \Yii::$app->authManager;
    $role = $this->auth->getRole('root');

    $this->renameColumn('visitor', 'lang', 'lg');
    $this->execute('ALTER TABLE `visitor` CHANGE `lg` `lg` VARCHAR(6) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT \'en-EN\';');
    $this->execute('UPDATE `visitor` SET `lg` = \'en-EN\' WHERE `lg` = \'2\';;');
    $this->execute('UPDATE `visitor` SET `lg` = \'en-EN\' WHERE `lg` = \'0\';;');
    $this->execute('UPDATE `visitor` SET `lg` = \'fr\' WHERE `lg` = \'1\';');


    $this->execute('ALTER TABLE `visitor` CHANGE `creat` `create` TEXT NOT NULL;');
    $this->execute('UPDATE `visitor` SET `create` = from_unixtime(`create`);');
    $this->execute('ALTER TABLE `visitor` CHANGE `create` `create` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;');



    $this->createPermission(
      'VisitorView',
      'Visitor - просмотр (общая таблица)',
      [$role]
    );

    $this->createPermission(
      'VisitorUpdate',
      'Visitor  - редактирование',
      [$role]
    );

    $this->createPermission(
      'VisitorDelete',
      'Visitor - удаление',
      [$role]
    );

    $this->createPermission(
      'VisitorCreate',
      'Visitor - создание',
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
