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
        // Verify member table structure with both prefixed and non-prefixed names
        $memberTable = $this->db->schema->getTableSchema('{{%member}}');
        $memberTableNonPrefixed = $this->db->schema->getTableSchema('member');
        
        if ($memberTable === null && $memberTableNonPrefixed === null) {
            throw new \yii\base\Exception('Table member does not exist. Please create the member table first.');
        }

        // Use the existing table schema
        $actualMemberTable = $memberTable ?? $memberTableNonPrefixed;

        // Check if member table has an 'id' column
        $idColumn = $actualMemberTable->columns['id'] ?? null;
        if (!$idColumn) {
            throw new \yii\base\Exception('Member table does not have an "id" column.');
        }

        // Drop the table if it exists to prevent conflicts
        $this->dropTableIfExists('{{%payments}}');

        // Create payments table
        $this->createTable('{{%payments}}', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer(10)->unsigned()->notNull(),
            'payment_id' => $this->string()->notNull()->unique(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'payment_method' => $this->string()->notNull(),
            'transaction_id' => $this->string()->notNull()->unique(),
            'phone_number' => $this->string(),
            'status' => $this->string()->notNull()->defaultValue('completed'),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Create indexes
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
        
        // Add foreign key - use the appropriate table name
        $this->addForeignKey(
            'fk-payments-member_id',
            '{{%payments}}',
            'member_id',
            'member', // Use non-prefixed name to match original migration
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key first to avoid constraint errors
        $this->dropForeignKey('fk-payments-member_id', '{{%payments}}');
        
        // Drop indexes
        $this->dropIndex('idx-payments-transaction_id', '{{%payments}}');
        $this->dropIndex('idx-payments-payment_id', '{{%payments}}');
        $this->dropIndex('idx-payments-member_id', '{{%payments}}');
        
        // Drop table
        $this->dropTable('{{%payments}}');
    }

    /**
     * Custom method to safely drop table if it exists
     */
    protected function dropTableIfExists($table)
    {
        $tableSchema = $this->db->schema->getTableSchema($table);
        if ($tableSchema !== null) {
            $this->dropTable($table);
        }
    }
}