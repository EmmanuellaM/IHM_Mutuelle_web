<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "payments".
 *
 * @property int $id
 * @property int $member_id
 * @property string $payment_id
 * @property float $amount
 * @property string $payment_method
 * @property string $transaction_id
 * @property string|null $phone_number
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Member $member
 */
class Payment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payments';
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
            [['member_id', 'payment_id', 'amount', 'payment_method', 'transaction_id', 'status'], 'required'],
            [['member_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['payment_id', 'payment_method', 'transaction_id', 'phone_number', 'status'], 'string', 'max' => 255],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::class, 'targetAttribute' => ['member_id' => 'id']],
            [['transaction_id'], 'unique'],
            [['payment_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'payment_id' => 'Payment ID',
            'amount' => 'Montant',
            'payment_method' => 'Mode de paiement',
            'transaction_id' => 'ID Transaction',
            'phone_number' => 'Numéro de téléphone',
            'status' => 'Statut',
            'created_at' => 'Date de création',
            'updated_at' => 'Date de mise à jour',
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
     * Crée un nouveau paiement
     */
    public static function createPayment($member_id, $payment_id, $amount, $payment_method, $transaction_id, $phone_number = null)
    {
        $payment = new self();
        $payment->member_id = $member_id;
        $payment->payment_id = $payment_id;
        $payment->amount = $amount;
        $payment->payment_method = $payment_method;
        $payment->transaction_id = $transaction_id;
        $payment->phone_number = $phone_number;
        $payment->status = 'completed';
        
        return $payment->save() ? $payment : null;
    }

    /**
     * Retourne la liste des paiements d'un membre
     */
    public static function getMemberPayments($member_id)
    {
        return self::find()
            ->where(['member_id' => $member_id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    }

    /**
     * Formate la date pour l'affichage
     */
    public function getFormattedDate($attribute)
    {
        return Yii::$app->formatter->asDatetime($this->$attribute, 'php:d/m/Y H:i');
    }
}
