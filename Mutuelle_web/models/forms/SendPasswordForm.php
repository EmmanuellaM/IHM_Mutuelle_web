<?php
namespace app\models\forms;
use yii\base\Model;

class SendPasswordForm extends Model
{

    public $username;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'],'string','message' =>'Ce champs attend du texte'],
            [['username'],'required'],

        ];
    }


    /**
     * 
     * @return bool whether the creating new account was successful and email was sent
     */

     public function sendmail(){

        if(!$this->validate()){
            return null;
        }
     }

}