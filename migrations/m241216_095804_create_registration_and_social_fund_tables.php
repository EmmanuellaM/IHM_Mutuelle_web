<?php

use yii\db\Migration;

class m241216_095804_create_registration_and_social_fund_tables extends Migration
{
    public function safeUp()
    {
        // Create registration table
        $this->createTable('registration', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'registration_date' => $this->date()->notNull(),
            'payment_method' => $this->string(50)->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Create social_fund table
        $this->createTable('social_fund', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'contribution_date' => $this->date()->notNull(),
            'payment_method' => $this->string(50)->notNull(),
            'status' => $this->string(20)->notNull()->defaultValue('pending'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);

        // Add indexes
        $this->createIndex('idx-registration-member_id', 'registration', 'member_id');
        // $this->createIndex('idx-registration-exercise_id', 'registration', 'exercise_id'); // Commenté car exercise_id n'existe pas
        // $this->createIndex('idx-social_fund-exercise_id', 'social_fund', 'exercise_id'); // Commenté car exercise_id n'existe pas
        $this->createIndex('idx-social_fund-member_id', 'social_fund', 'member_id');

        // Add foreign keys
        $this->addForeignKey(
            'fk-registration-member_id',
            'registration',
            'member_id',
            'member',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-social_fund-member_id',
            'social_fund',
            'member_id',
            'member',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey('fk-registration-member_id', 'registration');
        $this->dropForeignKey('fk-social_fund-member_id', 'social_fund');

        // Drop tables
        $this->dropTable('registration');
        $this->dropTable('social_fund');
    }
}
