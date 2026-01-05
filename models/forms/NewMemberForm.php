<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 26/12/18
 * Time: 13:41
 */
/**
 * modified by vs code
 * User: William Momo
 * Date: 06/01/2023
 * Time: 11:21
 */
namespace app\models\forms;
use app\models\User;
use Yii;


use yii\base\Model;

class NewMemberForm extends Model
{
    public $username;
    public $name;
    public $first_name;
    public $tel;
    public $operator; // Opérateur téléphonique (MTN, Orange, Camtel)
    public $email;
    public $address;
    public $avatar;
    public $password;
    public $password_repeat;//Ici aussi


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','name','first_name','password','tel','email','address','password_repeat','operator'],'string','message' => 'Ce champ attend du texte'],
            [['username','name','first_name','tel','password','email','operator'],'required','message' => 'Ce champ est obligatoire'],
            [['email'],'email','message' => 'Ce champ attend un email'],
            [['tel'], 'validatePhoneByOperator'], // Validation personnalisée par opérateur
            [['password'], 'match', 'pattern' => '/^(?=.*[A-Z])(?=.*\d).{8,}$/', 'message' => 'Le mot de passe doit contenir au moins 8 caractères, une majuscule et un chiffre.'],
            [['avatar'],'image','message' => 'Ce champ attend une image'],
            ['password_repeat','compare','compareAttribute' => 'password'],
        ];//C'est ici qu'on définit les messages d'erreur sur la page 
    }
    
    /**
     * Validation personnalisée du numéro de téléphone en fonction de l'opérateur
     */
    public function validatePhoneByOperator($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $phone = $this->$attribute;
            $operator = $this->operator;
            
            // Patterns de validation par opérateur camerounais (préfixes à 2 chiffres)
            $patterns = [
                'MTN' => '/^(67|65)[0-9]{7}$/',
                'Orange' => '/^(69|65)[0-9]{7}$/',
                'Camtel' => '/^(23|24)[0-9]{7}$/',
            ];
            
            if (!isset($patterns[$operator])) {
                $this->addError($attribute, 'Opérateur invalide.');
                return;
            }
            
            if (!preg_match($patterns[$operator], $phone)) {
                $examples = [
                    'MTN' => '670123456, 651234567',
                    'Orange' => '690123456, 651234567',
                    'Camtel' => '231234567, 241234567',
                ];
                $this->addError($attribute, "Le numéro ne correspond pas à l'opérateur {$operator}. Exemples valides : {$examples[$operator]}");
            }
        }
    }
    
    /**
    * Signs user up.
    *
    * @return bool whether the creating new account was successful and email was sent
    */
   public function singup()
   {
       if (!$this->validate()) {
           return null;
       }
       
       $user = new User();
       $user->username = $this->username;
       $user->name = $this->name;
       $user->first_name = $this->first_name;
       $user->tel = $this->tel;
       $user->email = $this->email;
       $user->adress = $this->adress;
       $user->setPassword($this->password);
       $user->generateAuthKey();
       $user->generateEmailVerificationToken();

       return $user->save() && $this->sendEmail($user);
   }

      /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    public function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */

   /*** public function inscriptionMember($email)
    {

        $content = "<p>Email:" . $this->email . "</p>";
        $content .= "<p>Name:" . $this->name . "</p>";


        if ($this->validate()) {
            Yii::$app->mailer->compose("@app/mail/layouts/html", ["content" => $content])
                ->setTo($email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$this->email => $this->name])
                ->send();

            return true;
        }
        return false;
    }***/


    public function inscriptionmail($email)
    {
        return Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->send();
    }


}