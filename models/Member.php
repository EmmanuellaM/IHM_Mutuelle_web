<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 24/12/18
 * Time: 18:04
 */

namespace app\models;

use app\managers\FinanceManager;
use yii\db\ActiveRecord;

class Member extends ActiveRecord
{

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public function user() {
        return User::findOne($this->user_id);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getSavings()
    {
        return $this->hasMany(Saving::class, ['member_id' => 'id']);
    }

    public function activeBorrowing() {
        return Borrowing::findOne(['member_id' => $this,'state'=>true]);
    }

    public function savedAmount(Exercise $exercise=null) {
        if ($exercise) {
            $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
            return Saving::find()->where(['session_id' => $sessions,'member_id' => $this->id])->sum("amount");
        }
        return 0;
    }

    public function exerciseSavings(Exercise $exercise) {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        return Saving::find()->where(['session_id' => $sessions,'member_id' => $this->id])->orderBy('created_at',SORT_ASC)->all();
    }

    public function borrowedAmount(Exercise $exercise) {
        if (!$exercise) {
            return 0;
        }
        try {
            $sessions = Session::find()
                ->select('id')
                ->where(['exercise_id' => $exercise->id])
                ->column();
            if (empty($sessions)) {
                return 0;
            }
            return Borrowing::find()
                ->where(['member_id' => $this->id])
                ->andWhere(['session_id' => $sessions])
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            \Yii::error("Erreur lors de la récupération du montant emprunté: " . $e->getMessage());
            return 0;
        }
    }

    public function exerciseBorrowings(Exercise $exercise) {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        return Borrowing::find()->where(['session_id' => $sessions,'member_id' => $this->id])->orderBy('created_at',SORT_ASC)->all();
    }

    public function refundedAmount(Exercise $exercise) {
        if (!$exercise) {
            return 0;
        }
        try {
            $sessions = Session::find()
                ->select('id')
                ->where(['exercise_id' => $exercise->id])
                ->column();
            if (empty($sessions)) {
                return 0;
            }
            $borrowings = Borrowing::find()
                ->select('id')
                ->where(['member_id' => $this->id])
                ->andWhere(['session_id' => $sessions])
                ->column();
            if (empty($borrowings)) {
                return 0;
            }
            return Refund::find()
                ->where(['borrowing_id' => $borrowings])
                ->sum('amount') ?? 0;
        } catch (\Exception $e) {
            \Yii::error("Erreur lors de la récupération du montant remboursé: " . $e->getMessage());
            return 0;
        }
    }

    public function calculateTotalBorrowingSavings($borrowingSavings)
    {
        $sum = 0;
        foreach($borrowingSavings as $borrowingSaving){
            if (isset($borrowingSaving->percent)) {
                $percent = $borrowingSaving->percent;
                $borrowing = Borrowing::findOne($borrowingSaving->borrowing_id);
                if ($borrowing) {
                    $intendedAmount = FinanceManager::intendedAmountFromBorrowing($borrowing);
                    if (is_numeric($intendedAmount) && is_numeric($borrowing->amount)) {
                        $sum += ($percent / 100.0) * ($intendedAmount - $borrowing->amount);
                    }
                }
            }
        }
        return $sum;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Activation uniquement si inscription et fond social sont TOTALEMENT payés
            $activeExercise = Exercise::findOne(['active' => true]);
            
            if ($activeExercise) {
                $inscriptionOK = $this->inscription >= $activeExercise->inscription_amount;
                $socialCrownOK = $this->social_crown >= $activeExercise->social_crown_amount;

                if ($inscriptionOK && $socialCrownOK) {
                    $this->active = true;
                } else {
                    $this->active = false;
                }
            }
            return true;
        }
        return false;
    }

    public function interest(Exercise $exercise) {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        $savings = Saving::find()->select('id')->where(['member_id' => $this->id,'session_id' => $sessions])->column();
        $borrowingSavings = BorrowingSaving::find()->where(['saving_id' => $savings])->all();
        return $this->calculateTotalBorrowingSavings($borrowingSavings);
    }

    /**
     * Récupère le montant d'inscription pour un exercice donné
     * @param Exercise $exercise
     * @return float|int
     */
    public function getRegistrationAmount($exercise)
    {
        if (!$exercise) {
            return 0;
        }
        try {
            $registration = Registration::find()
                ->where(['member_id' => $this->id])
                ->andWhere(['exercise_id' => $exercise->id])
                ->one();
            return $registration ? $registration->amount : 0;
        } catch (\Exception $e) {
            \Yii::error("Erreur lors de la récupération du montant d'inscription: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Récupère le montant du fonds social pour un exercice donné
     * @param Exercise $exercise
     * @return float|int
     */
    public function getSocialFundAmount($exercise)
    {
        if (!$exercise) {
            return 0;
        }
        try {
            $socialFund = SocialFund::find()
                ->where(['member_id' => $this->id])
                ->andWhere(['exercise_id' => $exercise->id])
                ->one();
            return $socialFund ? $socialFund->amount : 0;
        } catch (\Exception $e) {
            \Yii::error("Erreur lors de la récupération du montant du fond social: " . $e->getMessage());
            return 0;
        }
    }

    public function administrator() {
        return Administrator::findOne($this->administrator_id);
    }

    /**
     * Get all unpaid help contributions for this member
     * @return Contribution[]
     */
    public function getUnpaidHelpContributions()
    {
        return Contribution::find()
            ->joinWith('help')
            ->where(['contribution.member_id' => $this->id, 'contribution.state' => false])
            ->andWhere(['help.state' => true]) // Only active helps
            ->all();
    }

    /**
     * Get total amount due for help contributions
     * @return float
     */
    public function getTotalHelpContributionsDue()
    {
        $contributions = $this->getUnpaidHelpContributions();
        $total = 0;
        foreach ($contributions as $contribution) {
            if ($contribution->help) {
                $total += $contribution->help->unit_amount;
            }
        }
        return $total;
    }

    /**
     * Get total amount already paid for help contributions
     * @return float
     */
    public function getTotalHelpContributionsPaid()
    {
        $paidContributions = Contribution::find()
            ->joinWith('help')
            ->where(['contribution.member_id' => $this->id, 'contribution.state' => true])
            ->all();
        
        $total = 0;
        foreach ($paidContributions as $contribution) {
            if ($contribution->help) {
                $total += $contribution->help->unit_amount;
            }
        }
        return $total;
    }

    /**
     * Get count of unpaid help contributions
     * @return int
     */
    public function getUnpaidHelpContributionsCount()
    {
        return count($this->getUnpaidHelpContributions());
    }
}