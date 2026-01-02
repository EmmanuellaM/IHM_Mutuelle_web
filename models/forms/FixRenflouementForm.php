<?php

namespace app\models\forms;

use yii\base\Model;

class FixRenflouementForm extends Model
{
    public $amount;

    public function rules()
    {
        return [
            [['amount'], 'required', 'message' => 'Ce champ est obligatoire'],
            ['amount', 'integer', 'min' => 1, 'message' => 'Ce champ attend un entier positif']
        ];
    }
}
