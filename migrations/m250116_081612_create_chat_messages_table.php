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
        // Supprimer l'ancienne table si elle existe
        if ($this->db->schema->getTableSchema('chat_messages')) {
            $this->dropTable('chat_messages');
        }

        // Créer la nouvelle table
        $this->createTable('chat_messages', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);

        // Créer les index
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

        // Ajouter les clés étrangères
        $this->addForeignKey(
            'fk-chat_messages-sender_id',
            'chat_messages',
            'sender_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-chat_messages-receiver_id',
            'chat_messages',
            'receiver_id',
            'user',
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
        $this->dropTable('chat_messages');
    }
}
