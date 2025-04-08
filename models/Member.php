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

    public function user() {
        return User::findOne($this->user_id);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
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
}