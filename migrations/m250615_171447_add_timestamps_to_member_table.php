<?php

use yii\db\Migration;

/**
 * Handles adding timestamps to table `member`.
 */
class m250615_171447_add_timestamps_to_member_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Vérifier si les colonnes existent déjà
        $schema = \Yii::$app->db->schema;
        $table = $schema->getTableSchema('member');
        
        if (!$table->getColumn('created_at')) {
            $this->addColumn('member', 'created_at', $this->integer()->notNull());
        }
        
        if (!$table->getColumn('updated_at')) {
            $this->addColumn('member', 'updated_at', $this->integer()->notNull());
        }

        // Mettre à jour les valeurs pour les enregistrements existants
        $this->update('member', [
            'created_at' => time(),
            'updated_at' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('member', 'created_at');
        $this->dropColumn('member', 'updated_at');
    }
}
