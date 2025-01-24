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
        // Vérifier si la table chat_messages existe
        if (!$this->db->schema->getTableSchema('chat_messages')) {
            $this->createTable('chat_messages', [
                'id' => $this->primaryKey(),
                'sender_id' => $this->integer()->notNull(),
                'receiver_id' => $this->integer()->notNull(),
                'message' => $this->text()->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer(),
            ]);

            // Ajouter les clés étrangères
            $this->addForeignKey(
                'fk-chat_messages-sender_id',
                'chat_messages',
                'sender_id',
                'user',
                'id',
                'CASCADE'
            );
        } else {
            // Si la table existe déjà, ajouter seulement la colonne receiver_id
            if (!$this->db->schema->getTableSchema('chat_messages')->getColumn('receiver_id')) {
                $this->addColumn('chat_messages', 'receiver_id', $this->integer()->notNull());
            }
        }

        // Ajouter la clé étrangère pour receiver_id
        $this->addForeignKey(
            'fk-chat_messages-receiver_id',
            'chat_messages',
            'receiver_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if ($this->db->schema->getTableSchema('chat_messages')) {
            $this->dropForeignKey('fk-chat_messages-receiver_id', 'chat_messages');
            $this->dropColumn('chat_messages', 'receiver_id');
        }
    }
}
