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
        $this->createTable('chat_message', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Ajoute une clé étrangère vers la table user
        $this->addForeignKey(
            'fk-chat_message-sender_id',
            'chat_message',
            'sender_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Crée un index sur created_at pour optimiser les requêtes d'ordre chronologique
        $this->createIndex(
            'idx-chat_message-created_at',
            'chat_message',
            'created_at'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-chat_message-sender_id', 'chat_message');
        $this->dropIndex('idx-chat_message-created_at', 'chat_message');
        $this->dropTable('chat_message');
    }
}
