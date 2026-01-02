<?php

use yii\db\Migration;

/**
 * Class m251230_092437_add_session_tracking_to_renflouement
 */
class m251230_092437_add_session_tracking_to_renflouement extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('renflouement', 'start_session_number', $this->integer()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-renflouement-next_exercise', 'renflouement');
        $this->addColumn('renflouement', 'deadline', $this->date()->notNull());
        $this->dropColumn('renflouement', 'start_session_number');
        $this->dropColumn('renflouement', 'next_exercise_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m251230_092437_add_session_tracking_to_renflouement cannot be reverted.\n";

        return false;
    }
    */
}
