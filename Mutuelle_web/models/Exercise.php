<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 27/12/18
 * Time: 23:04
 */

namespace app\models;


use yii\db\ActiveRecord;

class Exercise extends ActiveRecord
{
    public static function tableName()
    {
        return 'exercise';
    }

    public function rules()
    {
        return [
            [['year', 'interest', 'inscription_amount', 'social_crown_amount'], 'integer'],
            [['year', 'interest', 'inscription_amount', 'social_crown_amount'], 'required'],
            [['active'], 'boolean'],
            [['created_at'], 'safe'],
            [['administrator_id'], 'exist', 'skipOnError' => true, 'targetClass' => Administrator::className(), 'targetAttribute' => ['administrator_id' => 'id']],
        ];
    }

    public function attributeLabels()
    {
        return [
            'year' => 'Année',
            'interest' => 'Taux d\'intérêt (%)',
            'inscription_amount' => 'Montant de l\'inscription (XAF)',
            'social_crown_amount' => 'Montant du fond social (XAF)',
        ];
    }

    public function getAdministrator()
    {
        return $this->hasOne(Administrator::className(), ['id' => 'administrator_id']);
    }

    public function sessions()
    {
        return Session::find()->where(['exercise_id' => $this->id])->orderBy('created_at', SORT_ASC)->all();
    }

    /**
     * Calcul du montant total de l'exercice
     * @return float|int
     */
    public function exerciseAmount()
    {
        // Calculer le montant total des inscriptions
        $inscriptionAmount = $this->totalInscriptionAmount();
        
        // Calculer le montant total des fonds sociaux
        $socialCrownAmount = $this->totalSocialCrownAmount();
        
        // Calculer le solde final
        return $inscriptionAmount + $socialCrownAmount + $this->totalSavedAmount() + $this->totalRefundedAmount() - $this->totalBorrowedAmount() - $this->totalAgapeAmount();
    }

    /**
     * Calcul du montant total des inscriptions
     * @return float|int
     */
    public function totalInscriptionAmount() {
        // Vérifier si l'exercice est actif
        if ($this->active !== 1) {
            return 0;
        }
        
        // Calculer le montant total des inscriptions pour les membres actifs
        return (float) Member::find()
            ->where(['inscription' => 1])
            ->sum('inscription');
    }

    /**
     * Calcul du montant total des fonds sociaux
     * @return float|int
     */
    public function totalSocialCrownAmount() {
        // Vérifier si l'exercice est actif
        if ($this->active !== 1) {
            return 0;
        }
        
        // Calculer le montant total des fonds sociaux pour les membres actifs
        return (float) Member::find()
            ->where(['social_crown' => 1])
            ->sum('social_crown');
    }

    /**
     * Calcul du montant de renflouement par membre
     * @return float|int
     */
    public function renflouementAmount() {
        // Vérifier si l'exercice est actif
        if ($this->active !== 1) {
            return 0;
        }
        
        // Calculer le nombre de membres actifs
        $activeMembers = $this->numberofActiveMembers();
        
        // Si aucun membre actif, renvoyer 0
        if ($activeMembers <= 0) {
            return 0;
        }
        
        // Calculer le montant de renflouement par membre
        return $this->exerciseAmount() / $activeMembers;
    }

    /**
     * Nombre de membres actifs
     * @return int
     */
    public function numberofActiveMembers() {
        return (int) Member::find()
            ->where(['active' => 1])
            ->count();
    }
    public function totalSavedAmount() {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        return Saving::find()->where(['session_id' => $sessions])->sum('amount') ;
    }

    public function totalBorrowedAmount() {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        return Borrowing::find()->where(['session_id' => $sessions])->sum('amount') ;
    }

    public function totalRefundedAmount() {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        return Refund::find()->where(['session_id' => $sessions])->sum('amount') ;
    }


    public function totalAgapeAmount() {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        return Agape::find()->where(['session_id' => $sessions])->sum('amount') ;
    }

    public function interest() {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        $amount =(int) Borrowing::find()->select('ceil(sum(amount*interest/100))')->where(['session_id' => $sessions])->column()[0];
        return $amount;
    }

    public function sessionNumber() {
        return count( Session::findAll(['exercise_id' => $this->id]));
    }

    public function borrowings() {
        $c = 1;
        $sessions = Session::find()->select('id')->where(['exercise_id' => $this->id])->column();
        return Borrowing::find()->where(['session_id' => $sessions,'state' =>$c])->all();
    }

    public function numberofMembers()
    {
        return Member::find()->count();
    }



}