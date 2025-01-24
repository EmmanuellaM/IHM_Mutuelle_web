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
                [['date', 'year', 'interest'], 'required', 'message' => 'Ce champ est obligatoire'],
                ['interest', 'number', 'min' => 0, 'max' => 100, 'tooSmall' => 'Le taux d\'intérêt doit être au moins 0%', 'tooBig' => 'Le taux d\'intérêt doit être au maximum 100%'],
            ];
        }
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Année de l\'exercice',
            'date' => 'Date de la rencontre de la première session',
            'interest' => 'Taux d\'intérêt (%)',
        ];
    }
}