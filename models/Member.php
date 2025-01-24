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
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        return Borrowing::find()->where(['session_id' => $sessions,'member_id' => $this->id])->sum("amount");
    }

    public function exerciseBorrowings(Exercise $exercise) {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        return Borrowing::find()->where(['session_id' => $sessions,'member_id' => $this->id])->orderBy('created_at',SORT_ASC)->all();
    }

    public function refundedAmount(Exercise $exercise) {
        $sessions = Session::find()->select('id')->where(['exercise_id' => $exercise->id])->column();
        $borrowings = Borrowing::find()->select('id')->where(['member_id' => $this->id,'session_id' => $sessions])->column();
        return Refund::find()->where(['borrowing_id' => $borrowings ])->sum("amount");
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
        $registration = Registration::findOne(['member_id' => $this->id, 'exercise_id' => $exercise->id]);
        return $registration ? $registration->amount : 0;
    }

    /**
     * Récupère le montant du fonds social pour un exercice donné
     * @param Exercise $exercise
     * @return float|int
     */
    public function getSocialFundAmount($exercise)
    {
        $socialFund = SocialFund::findOne(['member_id' => $this->id, 'exercise_id' => $exercise->id]);
        return $socialFund ? $socialFund->amount : 0;
    }

    public function administrator() {
        return Administrator::findOne($this->administrator_id);
    }
}