<?php

use yii\db\Migration;

/**
 * Class m180731_182228_cafeEdit
 */
class m180731_182228_cafeEdit extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->renameColumn('cafe', 'adres_1', 'address');
      $this->renameColumn('cafe', 'tps', 'tps_code');
      $this->renameColumn('cafe', 'tvq', 'tvq_code');
      $this->dropColumn('cafe', 'adres_2');
      $this->addColumn("cafe",'tps_value',$this->float()->notNull());
      $this->addColumn("cafe",'tvq_value',$this->float()->notNull());
      $this->addColumn("cafe",'franchisee',$this->integer()->defaultValue(1));
      $this->addColumn("cafe",'currency',$this->string(3)->defaultValue("USD"));
      $this->addColumn("cafe",'timeZone',$this->string(30)->defaultValue("Etc/GMT+4"));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      echo "m180731_182228_cafeEdit cannot be reverted.\n";
      $this->renameColumn('cafe', 'address', 'adres_1');
      $this->renameColumn('cafe', 'tps_code', 'tps');
      $this->renameColumn('cafe', 'tvq_code', 'tvq');
      $this->addColumn("cafe",'adres_2',$this->string()->null());
      $this->dropColumn('cafe', 'tps_value');
      $this->dropColumn('cafe', 'tvq_value');
      $this->dropColumn('cafe', 'franchisee');
      $this->dropColumn('cafe', 'currency');
      $this->dropColumn('cafe', 'timeZone');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180731_182228_cafeEdit cannot be reverted.\n";

        return false;
    }
    */
}
