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

    public function intendedAmount() {
        return $this->amount+($this->interest/100.0)*$this->amount;
    }

    public function administrator() {
        return Administrator::findOne($this->administrator_id);
    }

    public function session() {
        return Session::findOne($this->session_id);
    }
    public function member() {
        return Member::findOne($this->member_id);
    }

    // Pour controler le maxBorrowingAmount
    public function checkBorrowingAmount($maxBorrowingAmount) {
        if ($this->amount > $maxBorrowingAmount) {
            $errorMessage = 'Le montant demandé est supérieur au montant maximum empruntable basé sur les épargnes de cette session : ' . $maxBorrowingAmount . ' XAF';
            echo "<script type='text/javascript'>
                document.addEventListener('DOMContentLoaded', () => {
                    event.preventDefault();
                    alert('$errorMessage');
                });
            </script>";
        }
    }

    /**
     * Calcule le nombre de sessions écoulées depuis la création de l'emprunt
     * @return int Nombre de sessions
     */
    public function getSessionsElapsed() {
        $borrowingSession = $this->session();
        if (!$borrowingSession) {
            return 0;
        }
        
        $exercise = Exercise::findOne($borrowingSession->exercise_id);
        if (!$exercise) {
            return 0;
        }
        
        // Compter toutes les sessions de l'exercice créées après la session d'emprunt
        $sessionsAfter = Session::find()
            ->where(['exercise_id' => $exercise->id])
            ->andWhere(['>=', 'created_at', $borrowingSession->created_at])
            ->count();
        
        return $sessionsAfter;
    }

    /**
     * Calcule le montant restant à rembourser (montant initial + intérêts actuels - remboursements)
     * @return float Montant restant
     */
    public function getRemainingAmount() {
        $intendedAmount = $this->intendedAmount();
        $refundedAmount = $this->refundedAmount();
        return max(0, $intendedAmount - $refundedAmount);
    }

    /**
     * Vérifie si l'emprunt doit recevoir des intérêts de pénalité
     * Critères : 
     * - Plus de 3 sessions écoulées
     * - Emprunt toujours actif (state = 1)
     * - Montant restant > 0
     * @return bool
     */
    public function shouldApplyPenaltyInterest() {
        if ($this->state != 1) {
            return false; // Emprunt déjà remboursé
        }
        
        if ($this->getRemainingAmount() <= 0) {
            return false; // Rien à rembourser
        }
        
        return $this->getSessionsElapsed() > 3;
    }

    /**
     * Applique les intérêts de pénalité sur le montant restant
     * @param float $interestRate Taux d'intérêt à appliquer (en %)
     * @return bool Succès de l'opération
     */
    public function applyPenaltyInterest($interestRate) {
        if (!$this->shouldApplyPenaltyInterest()) {
            return false;
        }
        
        $remainingAmount = $this->getRemainingAmount();
        $penaltyAmount = ($interestRate / 100.0) * $remainingAmount;
        
        // Recalculer le nouveau taux d'intérêt global
        // Nouveau montant total = montant initial + anciens intérêts + pénalité
        $newTotalAmount = $this->intendedAmount() + $penaltyAmount;
        $newInterestRate = (($newTotalAmount - $this->amount) / $this->amount) * 100;
        
        $this->interest = $newInterestRate;
        
        return true;
    }
}

