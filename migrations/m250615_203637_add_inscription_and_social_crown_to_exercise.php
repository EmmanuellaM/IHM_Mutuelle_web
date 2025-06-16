<?php

use yii\db\Migration;

/**
 * Class m250615_203637_add_inscription_and_social_crown_to_exercise
 */
class m250615_203637_add_inscription_and_social_crown_to_exercise extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('exercise', 'inscription_amount', $this->integer()->notNull()->defaultValue(0)->comment('Montant de l\'inscription'));
        $this->addColumn('exercise', 'social_crown_amount', $this->integer()->notNull()->defaultValue(0)->comment('Montant du fond social'));

        // Mise à jour des exercices existants avec les valeurs par défaut
        $this->update('exercise', [
            'inscription_amount' => 0,
            'social_crown_amount' => 0
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('exercise', 'inscription_amount');
        $this->dropColumn('exercise', 'social_crown_amount');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250615_203637_add_inscription_and_social_crown_to_exercise cannot be reverted.\n";

        return false;
    }
    */
}
