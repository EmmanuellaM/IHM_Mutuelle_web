<?php

use yii\db\Migration;

/**
 * Class m240115_120000_add_penalty_amount_to_borrowing
 */
class m240115_120000_add_penalty_amount_to_borrowing extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('borrowing', 'penalty_amount', $this->integer(10)->unsigned()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('borrowing', 'penalty_amount');
    }
}
