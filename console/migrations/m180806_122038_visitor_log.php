<?php

use yii\db\Migration;

/**
 * Class m180806_122038_visitor_log
 */
class m180806_122038_visitor_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->renameColumn('visitor_log', 'cafe', 'cafe_id');

      $this->execute('set time_zone = \'+4:00\';');

      $this->execute('ALTER TABLE `visitor_log` CHANGE `add_time` `add_time` TEXT NOT NULL;');
      $this->execute('UPDATE `visitor_log` SET `add_time` = from_unixtime(`add_time`);');
      $this->execute('ALTER TABLE `visitor_log` CHANGE `add_time` `add_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;');

      $this->execute('ALTER TABLE `visitor_log` CHANGE `finish_time` `finish_time` TEXT NOT NULL;');
      $this->execute('UPDATE `visitor_log` SET `finish_time` = from_unixtime(`finish_time`);');
      $this->execute('ALTER TABLE `visitor_log` CHANGE `finish_time` `finish_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;');

      $this->execute("ALTER TABLE `visitor_log` CHANGE `visitor_id` `visitor_id` INT(11) NULL;");
      $this->execute("UPDATE `visitor_log` SET `visitor_id` = NULL WHERE `visitor_id` = 0;");
      $this->execute("DELETE FROM `user` WHERE `user`.`id` = 0");

      $this->execute('ALTER TABLE `visitor_log` CHANGE `notice` `notice` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL;');
      $this->execute('ALTER TABLE `visitor_log` CHANGE `finish_time` `finish_time` DATETIME NULL;');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
      $this->renameColumn('visitor_log', 'cafe_id', 'cafe');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180806_122038_visitor_log cannot be reverted.\n";

        return false;
    }
    */
}
