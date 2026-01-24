<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 27/12/18
 * Time: 23:04
 */

namespace app\models;


use Yii;
use yii\db\ActiveRecord;

class Exercise extends ActiveRecord
{
    /**
     * Getter pour le statut virtuel basé sur 'active'.
     * @return string
     */
    public function getStatus()
    {
        return $this->active ? 'active' : 'closed';
    }

    /**
     * Setter pour le statut virtuel.
     * Met à jour 'active' en conséquence.
     * @param string $value
     */
    public function setStatus($value)
    {
        $this->active = ($value === 'active');
    }

    public static function tableName()
    {
        return 'exercise';
    }

    public function rules()
    {
        return [
            [['year', 'interest', 'inscription_amount', 'social_crown_amount'], 'integer'],
            [['year', 'interest', 'inscription_amount', 'social_crown_amount'], 'required'],
            [['penalty_rate'], 'number'], 
            [['active'], 'boolean'],
            [['created_at'], 'safe'],
            [['status'], 'string'],
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
            'penalty_rate' => 'Taux de pénalité (%)',
            'status' => 'Statut',
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
        // Fallback: Utiliser la table Member car la table Registration semble vide
        // On somme simplement la colonne inscription de tous les membres
        return (float) Member::find()->sum('inscription');
    }

    /**
     * Calcul du montant total des fonds sociaux
     * @return float|int
     */
    public function totalSocialCrownAmount() {
        // Fallback: Utiliser la table Member car la table SocialFund semble vide
        return (float) Member::find()->sum('social_crown');
    }

    /**
     * Calcul du montant de renflouement par membre
     * @return float|int
     */
    public function renflouementAmount() {
        // Utiliser la méthode calculateRenflouementPerMember qui calcule correctement
        // (Total Agape + Total Aides du Fonds Social) / Nombre Membres Actifs
        return $this->calculateRenflouementPerMember();
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


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenflouements()
    {
        return $this->hasMany(Renflouement::class, ['exercise_id' => 'id']);
    }

    /**
     * Vérifie si l'exercice peut être clôturé (12 sessions atteintes)
     * @return boolean
     */
    public function canBeClosed()
    {
        return $this->sessionNumber() >= 12 && $this->active;
    }

    /**
     * Clôture l'exercice et génère les renflouements
     * @param int $nextExerciseId ID de l'exercice suivant
     * @return boolean
     */
    public function closeExercise($nextExerciseId)
    {
        if (!$this->canBeClosed()) {
            return false;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // 1. Désactiver l'exercice
            $this->active = false;
            $this->status = 'closed';
            
            if (!$this->save()) {
                throw new \Exception("Erreur lors de la sauvegarde de l'exercice");
            }

            // 2. Calculer le montant de renflouement
            $renflouementAmount = $this->calculateRenflouementPerMember();

            // 3. Générer les renflouements pour les membres actifs
            if ($renflouementAmount > 0) {
                $activeMembers = Member::find()->where(['active' => true])->all();
                
                foreach ($activeMembers as $member) {
                    $renflouement = new Renflouement();
                    $renflouement->member_id = $member->id;
                    $renflouement->exercise_id = $this->id; // Exercice d'origine
                    $renflouement->next_exercise_id = $nextExerciseId; // Exercice de paiement
                    $renflouement->amount = $renflouementAmount;
                    $renflouement->status = Renflouement::STATUS_PENDING;
                    $renflouement->start_session_number = 1; // Commence à la session 1 du nouvel exercice
                    
                    if (!$renflouement->save()) {
                        throw new \Exception("Erreur lors de la création du renflouement pour le membre " . $member->id . ": " . implode(', ', $renflouement->getErrorSummary(true)));
                    }
                }
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error("Erreur lors de la clôture de l'exercice: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcule le montant de renflouement par membre
     * Formule: (Fonds Social Max - Agape Annuel - Total Aides) / Nombre Membres Actifs
     */
    public function calculateRenflouementPerMember()
    {
        $activeMembers = $this->numberofActiveMembers();
        if ($activeMembers <= 0) return 0;

        // Formule demandée : (Agapes + Aides) / Membres Actifs
        // On ne tient plus compte du fonds social max ou collecté.
        // On repartit simplement les dépenses.

        $totalAgape = $this->totalAgapeAmount();
        $totalHelps = $this->getTotalHelpsFromSocialFund();
        $totalExpenses = $totalAgape + $totalHelps;
        
        $amount = $totalExpenses / $activeMembers;
        
        return $amount > 0 ? ceil($amount) : 0;
    }

    public function getTotalHelpsFromSocialFund()
    {
        // Supposons que Help a une colonne 'amount_from_social_fund' (ajoutée par migration)
        // Mais Help n'est pas lié directement à Exercise, il est lié à Session ou Date.
        // On doit trouver les helps de cet exercice.
        // Les Helps ont une date ? Ou linked to session ?
        // Help a 'created_at'.
        // Ou on passe par les sessions de l'exercice pour trouver les helps ?
        // Help n'a pas de session_id (on l'a vu lors du delete script).
        
        // On doit filtrer par date.
        // Dates de l'exercice: du 1er Janvier de l'année au 31 Dec... ou basé sur les sessions ?
        // Utilisons created_at BETWEEN start AND end de l'exercice.
        
        $startDate = $this->year . '-01-01 00:00:00';
        $endDate = $this->year . '-12-31 23:59:59';
        
        return (float) Help::find()
            ->where(['between', 'created_at', $startDate, $endDate])
            ->sum('amount_from_social_fund');
    }
}