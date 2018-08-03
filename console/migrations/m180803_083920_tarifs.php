<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m180803_083920_tarifs
 */
class m180803_083920_tarifs extends Migration
{

  public $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('tarifs', [
          'id' => Schema::TYPE_PK,
          'cafe_id'                => Schema::TYPE_INTEGER . ' NOT NULL',
          'min_sum'           => Schema::TYPE_FLOAT . ' NOT NULL',
          'max_sum'            => Schema::TYPE_FLOAT . ' NOT NULL',
          'first_hour'        => Schema::TYPE_FLOAT . ' NOT NULL',
          'next_hour'        => Schema::TYPE_FLOAT . ' NOT NULL',
          'start_visit'        => Schema::TYPE_FLOAT . ' NULL DEFAULT 1',
          'active'        => Schema::TYPE_BOOLEAN . ' NOT NULL DEFAULT 1',
      ], $this->tableOptions);

      $this->addForeignKey (
          'fk_tarifs_cafe_id',
          'tarifs',
          'cafe_id',
          'cafe',
          'id'
      );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->dropTable('tarifs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180803_083920_tarifs cannot be reverted.\n";

        return false;
    }
    */
}
