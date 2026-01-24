<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 27/12/18
 * Time: 22:35
 */

namespace app\models;


use yii\db\ActiveRecord;


class Session extends ActiveRecord
{

    public function totalAmount() {
        return $this->savedAmount()+$this->refundedAmount()-$this->borrowedAmount();
    }

    public function savedAmount(){
        return Saving::find()->where(['session_id' => $this->id])->sum('amount');
    }

    public function borrowedAmount() {
        return Borrowing::find()->where(['session_id' => $this->id])->sum('amount');
    }

    public function refundedAmount()  {
        return Refund::find()->where(['session_id' => $this->id])->sum('amount');
    }

    public function agapeAmount(){
        return Agape::find()->where(['session_id' => $this->id])->sum('amount');
    }

    public function date() {
        return (new \DateTime($this->date))->format("d-m-Y");
    }
    public function date_d_écheance_emprunt(){

       $date = new \DateTime($this->date);
       $date->add(new \DateInterval('P3M'));

        return $date->format("d-m-Y");

    }

    public function number() {
        $exercise = $this->exercise();
        $i = 0;
        foreach ($exercise->sessions() as $session) {
            $i++;
            if ($session->id ==  $this->id)
                return $i;
        }
        return 0;
    }

    public function exercise() {
        return Exercise::findOne($this->exercise_id);
    }
    public function getMonthName()
    {
        setlocale(LC_TIME, 'fr_FR.UTF-8');
        return strftime('%B', strtotime($this->date));
    }

    /**
     * AFTER SAVE: 
     * 1. Check for renflouements and deactivate members if it's the 4th session
     * 2. Apply penalties to borrowings every 3 sessions
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($insert) {
            // ===== RENFLOUEMENT: Désactivation après 4 sessions =====
            if ($this->number() >= 4) {
                // Trouver tous les renflouements liés à cet exercice qui ne sont pas encore payés
                $renflouements = Renflouement::find()
                    ->where(['next_exercise_id' => $this->exercise_id])
                    ->andWhere(['!=', 'status', Renflouement::STATUS_PAID])
                    ->all();
                
                foreach ($renflouements as $renflouement) {
                    $member = $renflouement->member;
                    if ($member && $member->active) {
                        $member->active = false;
                        if ($member->save(false)) {
                            // Mettre à jour le statut du renflouement
                            $renflouement->status = Renflouement::STATUS_LATE;
                            $renflouement->save(false);
                            
                            \Yii::warning("Membre {$member->id} désactivé car renflouement non payé à la session " . $this->number());
                        }
                    }
                }
            }

        }

        // ===== NOUVELLE LOGIQUE EMPRUNTS (Exécutée sur Insert ET Update) =====
        // Cela permet le rattrapage si on sauvegarde à nouveau la session
        $activeBorrowings = Borrowing::find()
            ->where(['state' => true])
            ->all();

        foreach ($activeBorrowings as $borrowing) {
            $sessionsElapsed = $borrowing->getSessionsElapsed();
            $sessionsElapsed = $borrowing->getSessionsElapsed();

            // LOGIQUE UNIFIÉE: TOUS LES 3 MOIS
            if ($sessionsElapsed > 0 && $sessionsElapsed % 3 == 0) {
                
                $member = $borrowing->member();
                $exercise = $this->exercise();
                
                // Déterminer le type de déduction à appliquer
                $applyPenaltyM = false;
                
                if ($sessionsElapsed == 3) {
                    // MOIS 3 : Toujours l'intérêt standard (3000)
                    $applyPenaltyM = false;
                } else {
                    // MOIS 6+ : Conditionnel (Solvabilité)
                    // Utilisation de la méthode centralisée de l'état insolvable
                    if ($member->isInsolvent($exercise)) {
                        $applyPenaltyM = true;
                    } else {
                        // SOLVABLE -> Intérêt Standard
                        $applyPenaltyM = false;
                    }
                }
                
                // Calcul du montant et libellé
                $amountToDeduct = 0;
                $logMessage = "";
                
                if ($applyPenaltyM) {
                    // Pénalité m
                    $rate = $exercise->penalty_rate ? $exercise->penalty_rate : 0;
                    if ($rate > 0) {
                        $amountToDeduct = ($borrowing->amount * $rate) / 100;
                        $logMessage = "Application Pénalité m Taux $rate% (Mois $sessionsElapsed, Insolvable)";
                    }
                } else {
                    // Intérêt Standard
                    $rate = $borrowing->interest;
                    if ($rate > 0) {
                        $amountToDeduct = ($borrowing->amount * $rate) / 100;
                        $logMessage = "Application Intérêt Standard Taux $rate% (Mois $sessionsElapsed)";
                    }
                }
                
                // Application de la déduction (si > 0)
                if ($amountToDeduct > 0) {
                    // Vérifier doublon (Session courante + Montant proche)
                    $alreadyApplied = Saving::find()
                        ->where(['member_id' => $member->id])
                        ->andWhere(['session_id' => $this->id])
                        ->andWhere(['<', 'amount', 0])
                        ->andWhere(['between', 'amount', -$amountToDeduct - 10, -$amountToDeduct + 10])
                        ->exists();
                        
                    if (!$alreadyApplied) {
                        \Yii::info("$logMessage pour Emprunt #{$borrowing->id}");
                        
                        $saving = new Saving();
                        $saving->member_id = $member->id;
                        $saving->session_id = $this->id;
                        $saving->amount = -$amountToDeduct;
                        $saving->administrator_id = $this->administrator_id;
                        
                        if (!$saving->save()) {
                            \Yii::error("Echec application déduction: " . json_encode($saving->errors));
                        } else {
                            // NOTIFICATION UTILISATEUR
                            // "On signale l'utilisateur quand on lui envoie genre on l'avertit"
                            try {
                                $user = $member->user; // Relation via Member
                                if ($user) {
                                    \app\managers\MailManager::alert_penalty($user, $member, $amountToDeduct, $logMessage);
                                }
                            } catch (\Exception $e) {
                                \Yii::error("Mail fail: ".$e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }
}