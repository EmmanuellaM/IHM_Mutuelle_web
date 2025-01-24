<?php
namespace app\models\forms;

use yii\base\Model;

class FixSocialCrownForm extends Model
{
    public $amount;
    public $id;

    /**
     * Définir les règles de validation
     */
    public function rules()
    {
        return [
            [['amount', 'id'], 'required'], // Les champs sont obligatoires
            ['amount', 'number', 'min' => 1], // amount doit être un nombre >= 1
            ['id', 'integer'], // id doit être un entier
        ];
    }

    /**
     * Définir les étiquettes des champs (optionnel)
     */
    public function attributeLabels()
    {
        return [
            'amount' => 'Montant à payer',
            'id' => 'Identifiant du membre',
        ];
    }
}
