<?php

use yii\db\Migration;

/**
 * Handles adding column `contributedAmount` to table `help`.
 */
class m241220_130000_add_contributed_amount_to_help extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('help', 'contributedAmount', $this->integer(10)->unsigned()->after('amount'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('help', 'contributedAmount');
    }
}
