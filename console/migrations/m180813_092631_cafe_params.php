<?php

use yii\db\Migration;

/**
 * Class m180813_092631_cafe_params
 */
class m180813_092631_cafe_params extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->execute('SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE=\'TRADITIONAL,ALLOW_INVALID_DATES\';');
      $this->execute('SET SQL_MODE=\'ALLOW_INVALID_DATES\';');

      $this->addColumn("cafe",'params_id',$this->integer()->notNull()->defaultValue(1));
      $this->addColumn("cafe",'vat_value',$this->string());
      $this->addColumn("cafe",'logo',$this->string());

      $this->createTable('cafe_params', [
          'id' => $this->primaryKey(),
          'name'=> $this->string(20)->notNull(),
          'vat_list'=> $this->string(1000)->defaultValue("{\"tps\":\"794470914RT0001\",\"tvq\":\"1225055111TQ0001\"}"),
          'time_zone'=> $this->string(30)->defaultValue("Etc/GMT+4"),
        'datetime'=> $this->string(30)->defaultValue("Y-m-d H:i:s"),
        'datetime_js'=> $this->string(30)->defaultValue("Y-m-d H:i:s"),
        'date'=> $this->string(30)->defaultValue("Y-m-d"),
        'date_js'=> $this->string(30)->defaultValue("Y-m-d"),
        'time'=> $this->string(30)->defaultValue("H:i"),
        'time_js'=> $this->string(30)->defaultValue("H:i"),
      ]);


      $this->dropColumn('cafe', 'timeZone');
      $this->dropColumn('cafe', 'tps_code');
      $this->dropColumn('cafe', 'tvq_code');
      $this->dropColumn('cafe', 'tps_value');
      $this->dropColumn('cafe', 'tvq_value');

      $params= new \frontend\modules\cafe\models\CafeParams();
      $params->id=1;
      $params->name="Canada";
      $params->vat_list="[{\"code\":\"tps\",\"name\":\"tps\",\"value\":\"1\",\"add_to_cost\":true,\"only_for_base_cost\":true},{\"code\":\"tvq\",\"name\":\"Tvq\",\"value\":\"0.5\",\"add_to_cost\":true,\"only_for_base_cost\":true}]";
      $params->save();

      $this->addForeignKey (
          'fk_cafe_to_params_id',
          'cafe',
          'params_id',
          'cafe_params',
          'id'
      );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180813_092631_cafe_params cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180813_092631_cafe_params cannot be reverted.\n";

        return false;
    }
    */
}
