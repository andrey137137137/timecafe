<?php

use frontend\modules\users\models\Users;
use yii\db\Migration;

/**
 * Class m180724_181935_edit_user_table
 */
class m180724_181935_edit_user_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->renameColumn('user', 'user', 'name');
    $this->renameColumn('user', 'cafe', 'cafe_id');
    $this->addColumn('user', "lg", $this->string(10)->defaultValue('en-En'));
    $this->addColumn("user", 'franchisee', $this->integer()->defaultValue(1));

    $users = Users::find()->all();
    foreach ($users as $user) {
      $user->new_password = $user->pass;
      if($user->id==0){
        $user->franchisee=0;
      }
      $user->save();
    }

  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    echo "m180724_181935_edit_user_table cannot be reverted.\n";
    $this->renameColumn('user', 'name', 'user');
    $this->renameColumn('user', 'cafe_id', 'cafe');
    $this->dropColumn('user', 'franchisee');
  }

  /*
  // Use up()/down() to run migration code without a transaction.
  public function up()
  {

  }

  public function down()
  {
      echo "m180724_181935_edit_user_table cannot be reverted.\n";

      return false;
  }
  */
}
