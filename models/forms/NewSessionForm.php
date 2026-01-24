<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 27/12/18
 * Time: 22:21
 */

namespace app\models\forms;


use app\models\Exercise;
use yii\base\Model;

class NewSessionForm extends Model
{
    public $year;
    public $date;
    public $interest;
    public $inscription_amount;
    public $social_crown_amount;
    public $penalty_rate;

    public function rules()
    {
        if (Exercise::findOne(['active' => true])) {
            return [
                ['date', 'date', 'format' => 'yyyy-M-d', 'message' => 'Ce champ attend une date'],
                ['date', 'required', 'message' => 'Ce champ est obligatoire']
            ];
        } else {
            return [
                ['year', 'integer'],
                ['date', 'date', 'format' => 'yyyy-M-d', 'message' => 'Ce champ attend une date'],
                [['date', 'year', 'interest', 'inscription_amount', 'social_crown_amount', 'penalty_rate'], 'required', 'message' => 'Ce champ est obligatoire'],
                ['interest', 'number', 'min' => 0, 'max' => 100, 'tooSmall' => 'Le taux d\'intérêt doit être au moins 0%', 'tooBig' => 'Le taux d\'intérêt doit être au maximum 100%'],
                ['penalty_rate', 'number', 'min' => 0, 'max' => 100, 'tooSmall' => 'Le taux de pénalité doit être au moins 0%', 'tooBig' => 'Le taux de pénalité doit être au maximum 100%'],
                ['inscription_amount', 'integer', 'min' => 0, 'message' => 'Le montant doit être un nombre positif'],
                ['social_crown_amount', 'integer', 'min' => 0, 'message' => 'Le montant doit être un nombre positif'],
            ];
        }
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Année de l\'exercice',
            'date' => 'Date de la rencontre de la première session',
            'interest' => 'Taux d\'intérêt (%)',
            'inscription_amount' => 'Montant de l\'inscription (XAF)',
            'social_crown_amount' => 'Montant du fond social (XAF)',
            'penalty_rate' => 'Taux de pénalité (%)',
        ];
    }
}