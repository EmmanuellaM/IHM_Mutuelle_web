<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * Class SocialFund
 * @package app\models
 *
 * @property int $id
 * @property int $member_id
 * @property int $exercise_id
 * @property float $amount
 * @property string $created_at
 */
class SocialFund extends ActiveRecord
{
    /**
     * @return string
     */
    public static function tableName()
    {
        return 'social_fund';
    }

    /**
     * @return array[]
     */
    public function rules()
    {
        return [
            [['member_id', 'exercise_id', 'amount'], 'required'],
            [['member_id', 'exercise_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at'], 'safe']
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExercise()
    {
        return $this->hasOne(Exercise::class, ['id' => 'exercise_id']);
    }
}
