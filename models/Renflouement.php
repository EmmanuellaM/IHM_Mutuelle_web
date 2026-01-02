<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "renflouement".
 *
 * @property int $id
 * @property int $member_id
 * @property int $exercise_id
 * @property float $amount
 * @property float $paid_amount
 * @property string $status
 * @property string $deadline
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Exercise $exercise
 * @property Member $member
 */
class Renflouement extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'en_attente';
    const STATUS_PARTIAL = 'partiel';
    const STATUS_PAID = 'paye';
    const STATUS_LATE = 'en_retard';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'renflouement';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'exercise_id', 'next_exercise_id', 'amount', 'start_session_number'], 'required'],
            [['member_id', 'exercise_id', 'next_exercise_id', 'start_session_number'], 'integer'],
            [['amount', 'paid_amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string'],
            [['exercise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exercise::class, 'targetAttribute' => ['exercise_id' => 'id']],
            [['next_exercise_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exercise::class, 'targetAttribute' => ['next_exercise_id' => 'id']],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::class, 'targetAttribute' => ['member_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Membre',
            'exercise_id' => 'Exercice d\'Origine',
            'next_exercise_id' => 'Exercice de Paiement',
            'amount' => 'Montant',
            'paid_amount' => 'Montant Payé',
            'status' => 'Statut',
            'start_session_number' => 'Session de Départ',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
        ];
    }

    /**
     * Gets query for [[Exercise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExercise()
    {
        return $this->hasOne(Exercise::class, ['id' => 'exercise_id']);
    }

    /**
     * Gets query for [[NextExercise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNextExercise()
    {
        return $this->hasOne(Exercise::class, ['id' => 'next_exercise_id']);
    }

    /**
     * Gets query for [[Member]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * Calculate sessions elapsed in the next exercise
     * @return int
     */
    public function getSessionsElapsed()
    {
        if (!$this->next_exercise_id) return 0;
        
        $sessions = Session::find()
            ->where(['exercise_id' => $this->next_exercise_id])
            ->orderBy(['id' => SORT_ASC])
            ->all();
            
        return count($sessions);
    }

    /**
     * Check if the 3-session deadline is passed
     * @return bool
     */
    public function isDeadlinePassed()
    {
        // Si on est à la 4ème session (ou plus), le délai est dépassé pour ceux qui n'ont pas payé
        return $this->getSessionsElapsed() >= 4;
    }

    /**
     * Calculate remaining amount to pay
     */
    public function getRemainingAmount()
    {
        return $this->amount - $this->paid_amount;
    }

    /**
     * Pay an amount
     */
    public function pay($amount)
    {
        if ($amount <= 0) {
            return false;
        }

        $remaining = $this->getRemainingAmount();
        
        $paymentAmount = min($amount, $remaining);
        $this->paid_amount += $paymentAmount;
        
        if ($this->paid_amount >= $this->amount) {
            $this->status = self::STATUS_PAID;
        } elseif ($this->paid_amount > 0) {
            $this->status = self::STATUS_PARTIAL;
        }

        return $this->save();
    }
}
