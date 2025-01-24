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
        $this->createTable('financial_aid', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'date' => $this->date()->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Ajouter une clé étrangère pour `member_id`
        $this->addForeignKey(
            'fk-financial_aid-member_id',
            'financial_aid',
            'member_id',
            'member',
            'id',
            'CASCADE'
        );
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-financial_aid-member_id', 'financial_aid');
        $this->dropTable('{{%financial_aid}}');
    }
}
