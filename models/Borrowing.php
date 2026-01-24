<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 28/12/18
 * Time: 11:53
 */

namespace app\models;


use yii\db\ActiveRecord;

class Borrowing extends ActiveRecord
{
    public function refundedAmount() {
        $result = Refund::find()->where(['borrowing_id' => $this->id])->sum("amount");
        return $result?$result:0;
    }

    /**
     * Montant initialement emprunté (Dette Brute)
     * NB: Dans la nouvelle logique, les intérêts sont précomptés.
     * Donc si j'emprunte 100k, je dois 100k.
     */
    public function intendedAmount() {
        return $this->amount;
    }

    /**
     * Montant réellement perçu par le membre (Dette Nette)
     * = Montant Emprunté - Intérêts
     */
    public function receivedAmount() {
        return $this->amount - ($this->interest/100.0)*$this->amount;
    }

    /**
     * Vérifie si l'emprunt respecte la règle des 5x l'épargne
     * @param float $memberSavings Épargne actuelle du membre
     * @return bool
     */
    public function checkCapacity($memberSavings) {
        $maxAmount = $memberSavings * 5;
        return $this->amount <= $maxAmount;
    }

    /**
     * Vérifie si l'emprunt est en défaut (retard > 6 mois coverage failure)
     * @param Member $member
     * @return bool
     */
    public function isInDefault($member) {
        if ($this->getSessionsElapsed() >= 6) { // Supposant que nous réimplémentons getSessionsElapsed ou similaire
             // Vérification couverture: (Epargne * 5) < Dette Restante ?
             $currentSavings = $member->savedAmount($this->session->exercise());
             $remainingDebt = $this->getRemainingAmount();
             
             if (($currentSavings * 5) < $remainingDebt) {
                 return true;
             }
        }
        return false;
    }

    /**
     * Réimplémentation nécessaire pour le calcul de durée
     */
    public function getSessionsElapsed() {
        $borrowingSession = $this->session;
        if (!$borrowingSession) return 0;
        
        $exercise = $borrowingSession->exercise();
        if (!$exercise) return 0;
        
        return Session::find()
            ->where(['exercise_id' => $exercise->id])
            ->andWhere(['>', 'id', $borrowingSession->id]) // Sessions APRES l'emprunt
            ->count();
    }

    public function getRemainingAmount() {
        $refundedAmount = $this->refundedAmount();
        // Dans nouvelle logique: intendedAmount est juste amount (100k)
        return max(0, $this->amount - $refundedAmount);
    }

    public function administrator() {
        return Administrator::findOne($this->administrator_id);
    }

    public function getSession() {
        return $this->hasOne(Session::class, ['id' => 'session_id']);
    }
    public function member() {
        return Member::findOne($this->member_id);
    }
}

