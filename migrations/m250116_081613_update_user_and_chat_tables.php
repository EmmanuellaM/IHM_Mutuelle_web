<?php

use yii\db\Migration;

/**
 * Class m250116_081613_update_user_and_chat_tables
 */
class m250116_081613_update_user_and_chat_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // 1. S'assurer que la table user a la bonne structure
        if (!$this->db->schema->getTableSchema('user')) {
            $this->createTable('user', [
                'id' => $this->primaryKey(),
                'name' => $this->string(),
                'first_name' => $this->string(),
                'tel' => $this->string(),
                'email' => $this->string(),
                'address' => $this->string(),
                'type' => $this->string(),
                'avatar' => $this->string(),
                'password' => $this->string(),
                'created_at' => $this->integer(),
            ]);
        }

        // 2. Supprimer l'ancienne table chat_messages si elle existe
        if ($this->db->schema->getTableSchema('chat_messages')) {
            $this->dropTable('chat_messages');
        }

        // 3. Créer la nouvelle table chat_messages
        $this->createTable('chat_messages', [
            'id' => $this->primaryKey(),
            'sender_id' => $this->integer()->notNull(),
            'receiver_id' => $this->integer()->notNull(),
            'message' => $this->text()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer(),
        ]);

        // 4. Créer les index
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

        // 5. Ajouter les clés étrangères
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
