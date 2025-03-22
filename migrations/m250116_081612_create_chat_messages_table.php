<?php

use yii\db\Migration;

/**
 * Class m250116_081612_create_chat_messages_table
 */
class m250116_081612_create_chat_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop table if it already exists to prevent conflicts
        $this->dropTableIfExists('chat_messages');

        // Create chat_messages table
        $this->createTable('chat_messages', [
            'id' => $this->primaryKey(),
            // Use unsigned integer to match user table
            'sender_id' => $this->integer()->unsigned()->notNull(),
            'receiver_id' => $this->integer()->unsigned()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);

        // Create indexes
        $this->createIndex(
            'idx-chat_messages-sender_id',
            'chat_messages',
            'sender_id'
        );

        $this->createIndex(
            'idx-chat_messages-receiver_id',
            'chat_messages',
            'receiver_id'
        );

        // Add foreign keys
        $this->addForeignKey(
            'fk-chat_messages-sender_id',
            'chat_messages',
            'sender_id',
            'user', // Use non-prefixed name
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-chat_messages-receiver_id',
            'chat_messages',
            'receiver_id',
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
        // Drop foreign keys first
        $this->dropForeignKey('fk-chat_messages-receiver_id', 'chat_messages');
        $this->dropForeignKey('fk-chat_messages-sender_id', 'chat_messages');

        // Drop indexes
        $this->dropIndex('idx-chat_messages-receiver_id', 'chat_messages');
        $this->dropIndex('idx-chat_messages-sender_id', 'chat_messages');

        // Drop table
        $this->dropTable('chat_messages');
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
