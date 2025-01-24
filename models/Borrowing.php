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
}

