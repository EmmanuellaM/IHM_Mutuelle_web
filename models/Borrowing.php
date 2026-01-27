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

    /**
     * Détermine si l'emprunt doit subir une pénalité lors de la clôture de session.
     * Basé sur la règle unifiée des 3 mois.
     */
    public function shouldApplyPenaltyInterest()
    {
        if (!$this->state) return false; // Uniquement si actif
        $elapsed = $this->getSessionsElapsed();
        return ($elapsed > 0 && $elapsed % 3 == 0);
    }

    /**
     * Applique la pénalité (Intérêt ou pénalité m)
     * @param float $interestRate Taux d'intérêt standard
     */
    public function applyPenaltyInterest($interestRate)
    {
        $member = $this->member();
        $session = Session::findOne(['active' => true]); // La session en cours
        if (!$session || !$member) {
            // Fallback si aucune session active (cas de clôture en cours)
            // On essaie de trouver la dernière session liée à l'exercice actif
            $exercise = Exercise::findOne(['active' => true]);
            if ($exercise) {
                $session = Session::find()
                    ->where(['exercise_id' => $exercise->id])
                    ->orderBy(['date' => SORT_DESC])
                    ->one();
            }
        }
        
        if (!$session || !$member) return;

        $exercise = $session->exercise();
        $elapsed = $this->getSessionsElapsed();

        // Déterminer le taux
        $rate = $interestRate;
        if ($elapsed >= 6 && $member->isInsolvent($exercise)) {
            $penaltyRate = $exercise->penalty_rate ?: 0;
            if ($penaltyRate > 0) $rate = $penaltyRate;
        }

        $amountToDeduct = ($this->amount * $rate) / 100;
        
        if ($amountToDeduct > 0) {
            // On vérifie si déjà appliqué pour cette session (pour éviter le double prélèvement avec afterSave)
            $alreadyApplied = Saving::find()
                ->where(['member_id' => $member->id, 'session_id' => $session->id])
                ->andWhere(['<', 'amount', 0])
                ->exists();

            if (!$alreadyApplied) {
                $saving = new Saving();
                $saving->member_id = $member->id;
                $saving->session_id = $session->id;
                $saving->amount = -$amountToDeduct;
                $saving->administrator_id = \Yii::$app->user->id ?? 1;
                
                if ($saving->save()) {
                    try {
                        $logMessage = ($rate == $interestRate) ? "Intérêt Standard" : "Pénalité m";
                        \app\managers\MailManager::alert_penalty($member->user(), $member, $amountToDeduct, $logMessage);
                    } catch (\Exception $e) {
                        \Yii::error("Mail penalty fail: " . $e->getMessage());
                    }
                }
            }
        }
    }
}

