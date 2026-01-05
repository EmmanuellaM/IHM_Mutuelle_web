<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%renflouement}}`.
 */
class m251229_155837_create_renflouement_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%renflouement}}', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->unsigned()->notNull(),
            'exercise_id' => $this->integer()->unsigned()->notNull(),
            'next_exercise_id' => $this->integer()->unsigned()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'paid_amount' => $this->decimal(10, 2)->defaultValue(0),
            'status' => $this->string()->defaultValue('en_attente'),
            'start_session_number' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
        
        // Add foreign keys
        $this->addForeignKey(
            'fk-renflouement-member_id',
            '{{%renflouement}}',
            'member_id',
            '{{%member}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-renflouement-exercise_id',
            '{{%renflouement}}',
            'exercise_id',
            '{{%exercise}}',
            'id',
            'CASCADE'
        );
        
        $this->addForeignKey(
            'fk-renflouement-next_exercise_id',
            '{{%renflouement}}',
            'next_exercise_id',
            '{{%exercise}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-renflouement-member_id', '{{%renflouement}}');
        $this->dropForeignKey('fk-renflouement-exercise_id', '{{%renflouement}}');
        $this->dropForeignKey('fk-renflouement-next_exercise_id', '{{%renflouement}}');
        $this->dropTable('{{%renflouement}}');
    }
}
