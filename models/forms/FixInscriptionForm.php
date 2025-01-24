<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 28/12/18
 * Time: 23:40
 */

namespace app\models\forms;


use yii\base\Model;

class FixInscriptionForm extends Model
{
    public $id;
    public $amount;
    //public $session_id;

    public function rules()
    {
        return [
            [['amount' ],'required','message' => 'Ce champ est obligatoire'],
            //[['id','session_id'],'integer','min' => 1],
            ['amount','integer','min' => 1,'message' => 'Ce champ attend un entier positif']
        ];
    }
}