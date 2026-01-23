<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 28/12/18
 * Time: 11:54
 */

namespace app\models;


use yii\db\ActiveRecord;

class Saving extends ActiveRecord
{
    public function administrator() {
        return Administrator::findOne($this->administrator_id);
    }
    public function getSession() {
        return $this->hasOne(Session::class, ['id' => 'session_id']);
    }
}