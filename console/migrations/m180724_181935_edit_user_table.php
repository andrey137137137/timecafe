<?php

use yii\db\Migration;
use frontend\modules\users\models\Users;

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
      $this->addColumn('user',"lg",$this->string(10)->defaultValue('en-En'));

      $users=Users::find()->all();
      foreach ($users as $user){
        $user->new_password=$user->pass;
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
        return true;
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
