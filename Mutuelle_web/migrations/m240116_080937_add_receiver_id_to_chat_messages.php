<?php

use yii\db\Migration;

/**
 * Class m240116_080937_add_receiver_id_to_chat_messages
 */
class m240116_080937_add_receiver_id_to_chat_messages extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Add receiver_id column if it doesn't exist
        if ($this->db->schema->getTableSchema('chat_messages') !== null &&
            !$this->db->schema->getTableSchema('chat_messages')->getColumn('receiver_id')) {
            
            $this->addColumn('chat_messages', 'receiver_id', $this->integer()->notNull());
            
            // Add foreign key only if the column was just added
            $this->addForeignKey(
                'fk-chat_messages-receiver_id',
                'chat_messages',
                'receiver_id',
                'user',
                'id',
                'CASCADE'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('chat_messages') !== null) {
            if ($this->db->schema->getTableSchema('chat_messages')->getColumn('receiver_id')) {
                $this->dropForeignKey('fk-chat_messages-receiver_id', 'chat_messages');
                $this->dropColumn('chat_messages', 'receiver_id');
            }
        }
    }
}
