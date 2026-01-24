<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 01/01/19
 * Time: 23:10
 */

namespace app\models\forms;


use yii\base\Model;

class SettingForm extends Model
{
    public $interest;
    public $social_crown;
    public $penalty_rate;
    public $inscription;


    public function rules()
    {
        return [
            [['interest','social_crown','inscription', 'penalty_rate'],'required','message' => 'Ce champ est obligatoire'],
            [['social_crown','inscription'],'integer','min' => 1,'message' => 'Ce champ attend un entier positif'],
            [['interest', 'penalty_rate'], 'number', 'min' => 0, 'message' => 'Ce champ attend un nombre positif']
        ];
    }
}