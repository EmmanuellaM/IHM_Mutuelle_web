<?php

use yii\db\Migration;

class m231219_create_registration_and_social_fund_tables extends Migration
{
    public function safeUp()
    {
        // Création de la table registration
        $this->createTable('registration', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'exercise_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Ajout des clés étrangères pour registration
        $this->addForeignKey(
            'fk-registration-member_id',
            'registration',
            'member_id',
            'member',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-registration-exercise_id',
            'registration',
            'exercise_id',
            'exercise',
            'id',
            'CASCADE'
        );

        // Création de la table social_fund
        $this->createTable('social_fund', [
            'id' => $this->primaryKey(),
            'member_id' => $this->integer()->notNull(),
            'exercise_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->notNull(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // Ajout des clés étrangères pour social_fund
        $this->addForeignKey(
            'fk-social_fund-member_id',
            'social_fund',
            'member_id',
            'member',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-social_fund-exercise_id',
            'social_fund',
            'exercise_id',
            'exercise',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('registration');
        $this->dropTable('social_fund');
    }
}
