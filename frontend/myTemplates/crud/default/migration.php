<?php
/**
 * This is the template for generating the model class of a specified table.
 */

/* @var $this yii\web\View */
/* @var $generator mootensai\enhancedgii\migration\Generator */
/* @var $migrationName string migration name */

echo "<?php\n";
?>

use yii\db\Schema;

class <?= $migrationName ?> extends \yii\db\Migration
{

  private $auth;
<?php if($generator->isSafeUpDown): ?>
  public function safeUp()
<?php else: ?>
  public function up()
<?php endif; ?>
  {
    //применить миграцию
    $this->auth = \Yii::$app->authManager;
    $role = $this->auth->getRole('admin');

    $this->createPermission(
      '<?=$rbacName;?>View',
      '<?=$rbacName;?> - просмотр (общая таблица)',
      [$role]
    );

    $this->createPermission(
      '<?=$rbacName;?>Update',
      '<?=$rbacName;?>  - редактирование',
      [$role]
    );

    $this->createPermission(
      '<?=$rbacName;?>Delete',
      '<?=$rbacName;?> - удаление',
      [$role]
    );

    $this->createPermission(
      '<?=$rbacName;?>Create',
      '<?=$rbacName;?> - создание',
      [$role]
    );
  }

<?php if($generator->isSafeUpDown): ?>
  public function safeDown()
<?php else: ?>
  public function down()
<?php endif; ?>
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
