<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payments}}`.
 */
class m240128_114500_create_payments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'payment_id' => $this->string()->notNull()->unique(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'payment_method' => $this->string()->notNull(),
            'transaction_id' => $this->string()->notNull()->unique(),
            'phone_number' => $this->string(),
            'status' => $this->string()->notNull()->defaultValue('completed'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Ajouter les clés étrangères
        $this->addForeignKey(
            'fk-payments-member_id',
            '{{%payments}}',
            'member_id',
            '{{%member}}',
            'id',
            'CASCADE'
        );

        // Créer les index
        $this->createIndex(
            'idx-payments-member_id',
            '{{%payments}}',
            'member_id'
        );
        $this->createIndex(
            'idx-payments-payment_id',
            '{{%payments}}',
            'payment_id'
        );
        $this->createIndex(
            'idx-payments-transaction_id',
            '{{%payments}}',
            'transaction_id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-payments-member_id', '{{%payments}}');
        $this->dropTable('{{%payments}}');
    }
}
