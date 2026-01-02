<?php

use yii\db\Migration;

/**
 * Class m251222_131709_add_amount_from_social_fund_to_help_table
 */
class m251222_131709_add_amount_from_social_fund_to_help_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('help', 'amount_from_social_fund', $this->decimal(10, 2)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m251222_131709_add_amount_from_social_fund_to_help_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251222_131709_add_amount_from_social_fund_to_help_table cannot be reverted.\n";

        return false;
    }
    */
}
