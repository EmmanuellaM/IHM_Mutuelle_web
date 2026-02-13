<?php

use yii\db\Migration;

/**
 * Class m250111_164500_create_chat_message_table
 */
class m250111_164500_create_chat_message_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop table if it already exists to prevent conflicts
        $this->dropTableIfExists('chat_message');

        // Create chat_message table
        $this->createTable('chat_message', [
            'id' => $this->primaryKey(),
            // Use unsigned integer to match user table
            'sender_id' => $this->integer()->unsigned()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Create index on sender_id
        $this->createIndex(
            'idx-chat_message-sender_id',
            'chat_message',
            'sender_id'
        );

        // Create index on created_at for chronological queries
        $this->createIndex(
            'idx-chat_message-created_at',
            'chat_message',
            'created_at'
        );

        // Add foreign key to user table
        $this->addForeignKey(
            'fk-chat_message-sender_id',
            'chat_message',
            'sender_id',
            'user', // Use non-prefixed name
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key first
        $this->dropForeignKey('fk-chat_message-sender_id', 'chat_message');

        // Drop indexes
        $this->dropIndex('idx-chat_message-created_at', 'chat_message');
        $this->dropIndex('idx-chat_message-sender_id', 'chat_message');

        // Drop table
        $this->dropTable('chat_message');
    }

    /**
     * Safely drop table if it exists
     */
    protected function dropTableIfExists($table)
    {
        $tableSchema = $this->db->schema->getTableSchema($table);
        if ($tableSchema !== null) {
            $this->dropTable($table);
        }
    }
}
