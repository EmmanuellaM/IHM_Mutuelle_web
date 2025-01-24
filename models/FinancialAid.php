<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "financial_aid".
 *
 * @property int $id
 * @property int $member_id
 * @property float $amount
 * @property string $date
 * @property string $description
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Member $member
 */
class FinancialAid extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'financial_aid';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'amount', 'date'], 'required'],
            [['member_id'], 'integer'],
            [['amount'], 'number'],
            [['date', 'created_at', 'updated_at'], 'safe'],
            [['description'], 'string'],
            [['status'], 'string'],
            [['status'], 'default', 'value' => 'pending'],
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
            'amount' => 'Montant',
            'date' => 'Date',
            'description' => 'Description',
            'status' => 'Statut',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
        ];
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
     * Calculate total financial aid for a member in a specific year
     *
     * @param int $memberId
     * @param int $year
     * @return float
     */
    public static function calculateYearlyTotal($memberId, $year)
    {
        return static::find()
            ->where(['member_id' => $memberId])
            ->andWhere(['YEAR(date)' => $year])
            ->sum('amount') ?? 0;
    }

    /**
     * Calculate remaining payment for a member
     *
     * @param int $memberId
     * @return float
     */
    public static function calculateRemainingPayment($memberId)
    {
        $member = Member::findOne($memberId);
        if (!$member) {
            return 0;
        }

        $socialCrown = $member->social_crown;
        $lastYearTotal = static::calculateYearlyTotal($memberId, date('Y') - 1);
        $paidAmount = static::calculateYearlyTotal($memberId, date('Y'));

        return ($socialCrown - $lastYearTotal) - $paidAmount;
    }
}
