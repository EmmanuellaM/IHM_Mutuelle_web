<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "chat_messages".
 *
 * @property int $id
 * @property int $sender_id
 * @property int $receiver_id
 * @property string $message
 * @property int $created_at
 * @property int $updated_at
 */
class ChatMessage extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chat_messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['message', 'sender_id', 'receiver_id'], 'required'],
            [['sender_id', 'receiver_id', 'created_at'], 'integer'],
            [['message'], 'string'],
            [['sender_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['sender_id' => 'id']],
            [['receiver_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['receiver_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sender_id' => 'Expéditeur',
            'receiver_id' => 'Destinataire',
            'message' => 'Message',
            'created_at' => 'Date d\'envoi',
            'updated_at' => 'Date de modification'
        ];
    }

    /**
     * Gets query for [[Sender]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSender()
    {
        return $this->hasOne(User::class, ['id' => 'sender_id']);
    }

    /**
     * Gets query for [[Receiver]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReceiver()
    {
        return $this->hasOne(User::class, ['id' => 'receiver_id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->created_at = time();
            }
            return true;
        }
        return false;
    }
}
