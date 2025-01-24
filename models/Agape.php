<?php

namespace app\models;

class Agape extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'agape';
    }

    public function rules()
    {
        return [
            [['amount'], 'required'],
            [['amount'], 'number'],
            [['session_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'agape_id' => 'Agape ID',
            'amount' => 'Amount',
            'session_id' => 'Session ID',

        ];
    }

    public function getSession()
    {
        return $this->hasOne(Session::class, ['id' => 'session_id']);
    }



    }