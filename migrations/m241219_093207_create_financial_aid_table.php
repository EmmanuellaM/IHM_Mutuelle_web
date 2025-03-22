<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%financial_aid}}`.
 */
class m241219_093207_create_financial_aid_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Drop table if it already exists to prevent conflicts
        $this->dropTableIfExists('financial_aid');

        // Create financial_aid table
        $this->createTable('financial_aid', [
            'id' => $this->primaryKey(),
            // Use unsigned integer to match member table
            'member_id' => $this->integer()->unsigned()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create index for member_id
        $this->createIndex(
            'idx-financial_aid-member_id', 
            'financial_aid', 
            'member_id'
        );

        // Add foreign key for member_id
        $this->addForeignKey(
            'fk-financial_aid-member_id',
            'financial_aid',
            'member_id',
            'member', // Use non-prefixed name
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign key first
        $this->dropForeignKey('fk-financial_aid-member_id', 'financial_aid');

        // Drop index
        $this->dropIndex('idx-financial_aid-member_id', 'financial_aid');

        // Drop table
        $this->dropTable('financial_aid');
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
