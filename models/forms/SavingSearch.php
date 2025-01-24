<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 24/12/18
 * Time: 17:43
 */

 namespace app\models;

 use yii\base\Model;
 
 class SavingSearch extends Model
 {
     public $session_id;
 
     public function rules()
     {
         return [
             [['session_id'], 'integer'],
         ];
     }
 }