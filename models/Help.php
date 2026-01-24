<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\Member;
use app\models\Contribution;
use app\models\Help_type;

class Help extends ActiveRecord
{
    public static function tableName()
    {
        return 'help';
    }

    public function rules()
    {
        return [
            [['member_id', 'help_type_id', 'amount'], 'required'],
            [['member_id', 'help_type_id'], 'integer'],
            [['amount', 'amount_from_social_fund'], 'number'],
        ];
    }

    /**
     * Relation avec le membre
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * Relation avec le type d'aide
     * @return \yii\db\ActiveQuery
     */
    public function getHelpType()
    {
        return $this->hasOne(Help_type::class, ['id' => 'help_type_id']);
    }

    /**
     * Relation avec les contributions
     * @return \yii\db\ActiveQuery
     */
    public function getContributions()
    {
        return $this->hasMany(Contribution::class, ['help_id' => 'id']);
    }

    /**
     * Méthode d'accès rapide au membre
     * @return Member|null
     */
    public function member()
    {
        return $this->getMember()->one();
    }

    /**
     * Méthode d'accès rapide au type d'aide
     * @return Help_type|null
     */
    public function helpType()
    {
        return $this->getHelpType()->one();
    }

    /**
     * Contributions en attente
     * @return \yii\db\ActiveQuery
     */
    public function getWaitedContributions()
    {
        return $this->getContributions()->where(['state' => false]);
    }

    /**
     * Calcul du montant total des contributions pour cette aide
     * @return float
     */
    public function getContributedAmount()
    {
        return Contribution::find()
            ->where(['help_id' => $this->id, 'state' => true])
            ->sum('amount') ?: 0;
    }

    /**
     * Définition de la propriété contributedAmount
     * @return float
     */
    public function getContributedAmountAttribute()
    {
        // Logique pour calculer ou retourner la valeur
        return $this->amount; // Exemple de retour, à adapter selon votre logique
    }

    /**
     * Calcul du montant restant à contribuer
     * @return float
     */
    public function remainingAmount()
    {
        $helpType = $this->helpType();
        return $helpType ? ($helpType->amount - $this->getContributedAmount()) : 0;
    }

    /**
     * Calcul du déficit
     * @return float
     */
    public function getDeficit()
    {
        return $this->amount - $this->amount_from_social_fund - $this->getContributedAmount();
    }

    /**
     * Intercepte l'aide pour payer les dettes du membre.
     * @return float Montant net à verser au membre après déduction des dettes.
     */
    public function interceptDebtDeduction()
    {
        $member = $this->member();
        if (!$member) return $this->amount;

        $exercise = Exercise::findOne(['active' => true]);
        if (!$exercise) return $this->amount;

        // Calculer la dette totale (restant à rembourser)
        $totalDebt = $member->borrowedAmount($exercise) - $member->refundedAmount($exercise); // Simplifié
        // Pour être plus précis, il faudrait itérer sur chaque emprunt et utiliser getRemainingAmount()
        // Mais member->borrowedAmount somme tout.
        
        $borrowings = $member->exerciseBorrowings($exercise);
        $realTotalDebt = 0;
        foreach ($borrowings as $borrowing) {
            $realTotalDebt += $borrowing->getRemainingAmount();
        }

        if ($realTotalDebt > 0) {
            $deducted = min($this->amount, $realTotalDebt);
            
            // Appliquer le remboursement
            // On doit créer des remboursements (Refund) pour chaque emprunt jusqu'à épuisement du montant déduit
            $remainingDeduction = $deducted;
            
            $session = Session::findOne(['active' => true]);
            if ($session) {
                foreach ($borrowings as $borrowing) {
                    if ($remainingDeduction <= 0) break;
                    
                    $debt = $borrowing->getRemainingAmount();
                    if ($debt > 0) {
                        $toPay = min($debt, $remainingDeduction);
                        
                        $refund = new Refund();
                        $refund->borrowing_id = $borrowing->id;
                        $refund->session_id = $session->id;
                        $refund->amount = $toPay;
                        $refund->save();
                        
                        $remainingDeduction -= $toPay;
                    }
                }
            }
            
            \Yii::$app->session->setFlash('info', "Une retenue de {$deducted} XAF a été appliquée sur l'aide pour rembourser les dettes.");
            return $this->amount - $deducted;
        }

        return $this->amount;
    }
}