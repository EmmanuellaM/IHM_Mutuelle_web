<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 31/12/18
 * Time: 12:31
 */

namespace app\models\forms;


use yii\base\Model;

class NewAdministratorForm extends Model
{
    public $username;
    public $name;
    public $first_name;
    public $tel;
    public $email;
    public $address;
    public $avatar;
    public $password;
    public $confirm_password;

    // public function rules()
    // {
    //     return [
    //         [['username','name','first_name','tel','password','email','address'],'string','message' => 'Ce champ doit être du texte'],
    //         [['username','name','first_name','tel','password','email'],'required','message' => 'Ce champ est obligatoire'],
    //         [['email'],'email','message' => 'Ce champ doit être un email'],
    //         [['avatar'],'image','message' => 'Ce champ attend une image','extensions'=>'jpg,jpeg,png,gif'],
    //     ];
    // }

    public function rules()
    {
        return [
            [['username', 'name', 'first_name', 'tel', 'password', 'email', 'address'], 'string', 'message' => 'Ce champ doit être du texte'],
            [['username', 'name', 'first_name', 'tel', 'password', 'email'], 'required', 'message' => 'Ce champ est obligatoire'],
            [['email'], 'email', 'message' => 'Ce champ doit être un email'],
            [['tel'],'match', 'pattern' => '/^[0-9]{9}$/', 'message' => 'Le numéro de téléphone doit avoir exactement 9 chiffres.'],
            [['avatar'], 'image', 'message' => 'Ce champ attend une image', 'extensions' => 'jpg,jpeg,png,gif'],
            [['password', 'confirm_password'], 'required', 'message' => 'Ce champ est obligatoire'],
            [['password'], 'compare', 'compareAttribute' => 'confirm_password', 'message' => 'Les mots de passe ne correspondent pas'],
        ];
    }
}