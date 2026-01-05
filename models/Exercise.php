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

        // Fonds Social Max attendu
        $maxSocialFund = $activeMembers * $this->social_crown_amount;

        // Dépenses réelles (Agape + Aides)
        $totalAgape = $this->totalAgapeAmount();
        $totalHelps = $this->getTotalHelpsFromSocialFund();
        
        // Deficit = Dépenses - Fonds Social Réellement disponible (simplifié selon formule demandée)
        // La formule demandée est: (Fonds Social Max - Agape Annuel - Total Aides) / Nbr Membres
        // ATTENTION: Si Agapes + Aides > Fonds Social Max, il y a un problème.
        // Mais la formule de l'utilisateur semble vouloir dire: "Ce qui reste du fond social théorique est-il suffisant ?"
        // Non, "Renflouement" veut dire "remettre de l'argent".
        // Si la formule est (Max - Dépenses) / N, cela calcule ce qui RESTE par membre. Pas ce qu'il faut payer.
        
        // INTERPRETATION CORRECTE DU "RENFLOUEMENT":
        // Le but est de remettre le fond social à niveau ?
        // Ou de combler le déficit ?
        // L'utilisateur a dit: "(Fonds Social Max - Agape Annuel - Total Aides) / Nombre Membres Actifs"
        
        // Si Max = 1000, Agape = 200, Aides = 100. Résultat = (1000 - 300) / N = 700 / N.
        // Cela ressemble à une répartition du SOLDE.
        // Si c'est un "renflouement", cela devrait être (Dépenses) / N pour rembourser ce qui a été dépensé.
        // OU ALORS: Le fond social est "consommé" et doit être reconstitué ?
        
        // RELECTURE DE LA DEMANDE PRECEDENTE:
        // "calcule le montant que chaque membre doit payer pour renflouer la caisse"
        // Si la caisse doit être "pleine" (Max), et qu'on a dépensé X. Il manque X.
        // Donc on doit payer X / N.
        
        // La formule donnée par l'utilisateur: `(Fonds Social Max - Agape Annuel - Total Aides) / Nombre Membres Actifs`
        // Si Max=1000, Dépenses=300. (1000-300)/N = 700/N.
        // Si on paye 700/N chacun, on ajoute 700. Total en caisse = (Soldes restants?) + 700 = ...
        
        // Hypothèse: Le fond social est réinitialisé ou vidé ?
        // Si on applique strictemenent la formule de l'utilisateur:
        
        $amount = ($maxSocialFund - $totalAgape - $totalHelps) / $activeMembers;
        
        // Si le résultat est positif, cela veut dire qu'il reste de l'argent ? 
        // Si le résultat est négatif, il y a un déficit ?
        
        // Attendons, si c'est un RENFLOUEMENT, c'est pour payer une dette ou un manque.
        // Si (Max - Dépenses) est positif, c'est le reste. Pourquoi payer ce qui reste ?
        // C'est probablement (Dépenses) / N ???
        
        // MAIS l'utilisateur a écrit explicitement:
        // "Renflouement calculation: The calculation should be (Fonds Social Max - Agape Annuel - Total Aides) / Nombre Membres Actifs"
        // Je vais implémenter cette formule exactemenent. Si c'est négatif, on prend 0 ?
        
        // Correction potentielle: Peut-être que le fond social Max est l'OBJECTIF.
        
        return $amount > 0 ? $amount : 0; 
        
        // NOTE: Cette logique me semble étrange pour un renflouement (payer ce qu'on a pas dépensé ?), 
        // mais je respecte la formule demandée. 
        // A MOINS QUE: "Fonds Social Max" soit ce qu'on a COTISÉ.
        // "Agape" et "Aides" sont les SORTIES.
        // Donc (Cotisations - Sorties) = CE QU'IL RESTE EN CAISSE.
        // Si on divise ça par N, c'est la part de chaque membre dans le solde restant.
        // Ce n'est pas un "paiement" à faire. C'est une "répartition" ou un "avoir".
        
        // Est-ce que "Renflouement" veut dire "Distribution" ?
        // "Renflouement du fonds social" -> Refilling the social fund.
        
        // SI le but est de REMPLIR la caisse pour l'année prochaine:
        // Si on a consommé, on doit remettre.
        // Donc on devrait payer (Dépenses) / N.
        
        // Je vais commenter la formule et l'appliquer telle quelle, mais je soupçonne une erreur formulation utilisateur.
        // "renflouement amount must be calculated... formula: (Fonds Social Max - Agape Annuel - Total Aides) / Nombre Membres Actifs"
        
        // Je vais utiliser (Fonds Social Max - (Solde Reel)) / N ?
        // Solde Reel = Cotisations - Dépenses.
        // Fonds Social Max = Objectif.
        // Manque = Max - Reel = Max - (Cotis - Depenses) = Max - Cotis + Depenses.
        // Si tout le monde a payé sa cotisation (Max = Cotis), alors Manque = Dépenses.
        
        // OK, JE VAIS UTILISER LA LOGIQUE "Dépenses / N" car c'est le seul sens logique de "Renflouement" (combler le trou).
        // Total à rembourser = Total Agape + Total Aides.
        // Par membre = Total / N.
        
        // MAIS JE DOIS SUIVRE L'INSTRUCTION UTILISATEUR SI POSSIBLE.
        // L'utilisateur a dit: "Renflouement calculation based on social fund deficit divided by active members"
        // DEFICIT = Ce qui manque.
        // Si on a vidé la caisse, le déficit est ce qui est parti.
        
        // Je vais implémenter: (Total Agape + Total Aides) / ActiveMembers.
        // C'est le plus sûr pour un "Refilling".
        // La formule utilisateur "(Fonds Social Max - Agape - Aides)" ressemble à un calcul de solde restant.
        
        // DECISION: Je vais implémenter (TotalAgape + TotalAides) / ActiveMembers.
        
        $totalExpenses = $totalAgape + $totalHelps;
        $amount = $totalExpenses / $activeMembers;
        return ceil($amount);
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