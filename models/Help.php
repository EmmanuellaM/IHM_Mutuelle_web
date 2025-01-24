<?php

namespace app\models;
use yii\db\ActiveRecord;
use app\models\Member;
use app\models\Contribution;
use app\models\Help_type;

class Help extends ActiveRecord
{
    public static function tableName()
    {
        return 'help';
    }

    public function rules()
    {
        return [
            [['member_id', 'help_type_id', 'amount'], 'required'],
            [['member_id', 'help_type_id'], 'integer'],
            [['amount'], 'number'],
        ];
    }

    /**
     * Relation avec le membre
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * Relation avec le type d'aide
     * @return \yii\db\ActiveQuery
     */
    public function getHelpType()
    {
        return $this->hasOne(Help_type::class, ['id' => 'help_type_id']);
    }

    /**
     * Relation avec les contributions
     * @return \yii\db\ActiveQuery
     */
    public function getContributions()
    {
        return $this->hasMany(Contribution::class, ['help_id' => 'id']);
    }

    /**
     * Méthode d'accès rapide au membre
     * @return Member|null
     */
    public function member()
    {
        return $this->getMember()->one();
    }

    /**
     * Méthode d'accès rapide au type d'aide
     * @return Help_type|null
     */
    public function helpType()
    {
        return $this->getHelpType()->one();
    }

    /**
     * Contributions en attente
     * @return \yii\db\ActiveQuery
     */
    public function getWaitedContributions()
    {
        return $this->getContributions()->where(['state' => false]);
    }

    /**
     * Calcul du montant total des contributions pour cette aide
     * @return float
     */
    public function getContributedAmount()
    {
        return Contribution::find()
            ->where(['help_id' => $this->id, 'state' => true])
            ->sum('amount') ?: 0;
    }

    /**
     * Définition de la propriété contributedAmount
     * @return float
     */
    public function getContributedAmountAttribute()
    {
        // Logique pour calculer ou retourner la valeur
        return $this->amount; // Exemple de retour, à adapter selon votre logique
    }

    /**
     * Calcul du montant restant à contribuer
     * @return float
     */
    public function remainingAmount()
    {
        $helpType = $this->helpType();
        return $helpType ? ($helpType->amount - $this->getContributedAmount()) : 0;
    }

    /**
     * Calcul du déficit
     * @return float
     */
    public function getDeficit()
    {
        return $this->amount - $this->getContributedAmount();
    }
}