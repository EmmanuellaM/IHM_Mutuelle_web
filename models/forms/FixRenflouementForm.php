<?php

namespace app\models\forms;

use yii\base\Model;

class FixRenflouementForm extends Model
{
    public $amount;

    public function rules()
    {
        return [
            ['amount', 'required', 'message' => 'Le montant est requis'],
            ['amount', 'number', 'min' => 1, 'message' => 'Le montant doit être supérieur à 0'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'amount' => 'Montant à payer',
        ];
    }
}
