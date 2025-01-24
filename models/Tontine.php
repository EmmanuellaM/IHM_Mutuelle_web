<?php

namespace app\models;

use yii\db\ActiveRecord;

class Tontine extends ActiveRecord
{

    public function contributions() {
        return ContributionTontine::findAll(['tontine_id'=> $this->id,'state' => true]);
    }

    public function waitedContributions() {
        return ContributionTontine::findAll(['tontine_id'=> $this->id,'state' => false]);
    }

    public function contributedAmount() {
        return ContributionTontine::find()->where(['tontine_id' => $this->id,'state' => true])->count() * $this->unit_amount;
    }

    public function member() {
        return Member::findOne($this->member_id);
    }

    public function TontineType() {
        return TontineType::findOne($this->tontine_type_id);
    }

    /**
     * Vérifie si un membre est déjà inscrit à un type de tontine
     * @param int $member_id
     * @param int $tontine_type_id
     * @return bool
     */
    public static function isAlreadyRegistered($member_id, $tontine_type_id)
    {
        return self::find()
            ->where(['member_id' => $member_id, 'tontine_type_id' => $tontine_type_id])
            ->exists();
    }
}