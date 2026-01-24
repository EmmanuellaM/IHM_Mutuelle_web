<?php

namespace app\managers;

use app\models\Borrowing;
use app\models\Saving;
use app\models\Exercise;
use app\models\Member;
use app\models\Refund; // Assurez-vous d'importer Refund
use DateTime;
use Yii;

class PenaltyManager
{
    /**
     * Vérifie les pénalités de 3 mois (intérêts non payés)
     * Règle : "Mois 3 : Prélèvement automatique du montant des intérêts sur l'épargne du membre si la dette n'est pas totalement remboursée."
     */
    public static function checkThreeMonthPenalties($exercise)
    {
        if (!$exercise) return;

        $activeBorrowings = Borrowing::find()->where(['state' => true])->all();

        foreach ($activeBorrowings as $borrowing) {
            // On ne traite que les emprunts de l'exercice en cours ? 
            // Ou tous les emprunts actifs ? Normalement emprunts de l'exercice.
            if ($borrowing->session->exercise_id != $exercise->id) continue;

            // Calculer le nombre de sessions écoulées depuis l'emprunt
            // getSessionsElapsed() doit être défini dans Borrowing ou calculé ici.
            // Supposons une logique simple basée sur l'ID de session ou la date.
            $elapsed = $borrowing->getSessionsElapsed();

            if ($elapsed == 3) {
                // Vérifier si la dette est totalement remboursée
                // Dette brute = intendedAmount() (qui est amount)
                // Montant remboursé = refundedAmount()
                $remaining = $borrowing->amount - $borrowing->refundedAmount();

                if ($remaining > 0) {
                    // Calculer le montant des intérêts
                    $interestAmount = $borrowing->amount * ($borrowing->interest / 100);

                    // Vérifier si une pénalité a déjà été appliquée ? (Logique à ajouter si besoin)
                    
                    // Prélever sur l'épargne la somme des intérêts
                    // 1. Trouver les économies du membre
                    $memberSavingsTotal = Saving::find()->where(['member_id' => $borrowing->member_id])->sum('amount');
                    
                    if ($memberSavingsTotal >= $interestAmount) {
                         // Créer une écriture de remboursement (Refund) pour diminuer la dette de ce montant ?
                         // Non, la règle dit "Prélèvement... si dette pas remboursée". 
                         // Si on prélève pour REMBOURSER la dette, alors on crée un Refund.
                         
                         $refund = new Refund();
                         $refund->borrowing_id = $borrowing->id;
                         $refund->member_id = $borrowing->member_id;
                         $refund->session_id = Session::findOne(['active' => true])->id; // Session actuelle
                         $refund->amount = $interestAmount;
                         $refund->administrator_id = 1; // Admin système ou ID 1
                         $refund->save();
                         
                         // Et on diminue l'épargne (Création d'une épargne NÉGATIVE)
                         $savingDed = new Saving();
                         $savingDed->member_id = $borrowing->member_id;
                         $savingDed->session_id = Session::findOne(['active' => true])->id;
                         $savingDed->amount = -$interestAmount; // Montant négatif
                         $savingDed->administrator_id = 1;
                         $savingDed->save();
                         
                         Yii::info("Pénalité 3 mois appliquée pour emprunt #{$borrowing->id}: {$interestAmount} prélevés.", 'penalties');
                    } else {
                        // Pas assez d'épargne... insolvabilité ?
                        Yii::warning("Pas assez d'épargne pour pénalité 3 mois emprunt #{$borrowing->id}", 'penalties');
                    }
                }
            }
        }
    }

    /**
     * Vérifie les pénalités de 6 mois + insolvabilité
     * Règle : "Si dette non remboursée et épargne < 5 * dette restante => Alerte + Pénalité manuelle"
     */
    public static function checkSixMonthPenalties($exercise)
    {
        $activeBorrowings = Borrowing::find()->where(['state' => true])->all();

        foreach ($activeBorrowings as $borrowing) {
            if ($borrowing->session->exercise_id != $exercise->id) continue;
            
            $elapsed = $borrowing->getSessionsElapsed();
            
            if ($elapsed >= 6) {
                $remainingDebt = $borrowing->amount - $borrowing->refundedAmount();
                if ($remainingDebt > 0) {
                     $memberSavings = Saving::find()->where(['member_id' => $borrowing->member_id])->sum('amount');
                     
                     if ($memberSavings < (5 * $remainingDebt)) {
                         // Calcul de la pénalité suggérée
                         $penaltyRate = $exercise->penalty_rate ? $exercise->penalty_rate : 0;
                         $suggestedPenalty = $remainingDebt * ($penaltyRate / 100);

                         // INSOLVABILITÉ ou ALERTE
                         Yii::warning("ALERTE: Emprunt #{$borrowing->id} (Membre: {$borrowing->member->id}) > 6 mois et couverture insuffisante. Pénalité suggérée (Taux: {$penaltyRate}%): " . number_format($suggestedPenalty, 0, ',', ' ') . " XAF", 'penalties');
                         
                         // Notification flash pour l'admin si en session interactive (optionnel, mais utile)
                         // Yii::$app->session->setFlash('warning', "Attention: Le membre {$borrowing->member->id} est en situation critique sur son emprunt #{$borrowing->id}. Pénalité suggérée: " . number_format($suggestedPenalty) . " FCFA");
                     }
                }
            }
        }
    }
}
