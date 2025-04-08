<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 30/12/18
 * Time: 11:58
 */

namespace app\managers;
use Yii;

class MemberSessionManager
{

    const place = "memberPlace";
    const head = "memberHead";


    public static function setHome($head = "home") {
        \Yii::$app->session->set(self::place,"home");
        \Yii::$app->session->set(self::head,$head);
    }

    public static function setProfil() {
        \Yii::$app->session->set(self::place,"profil");
        \Yii::$app->session->set(self::head,null);
    }

    public static function setMembers() {
        \Yii::$app->session->set(self::place,"members");
        \Yii::$app->session->set(self::head,null);
    }

    public static function setHelps() {
        \Yii::$app->session->set(self::place,"helps");
        \Yii::$app->session->set(self::head,"types-aide");
    }

    public static function setTontine() {
        \Yii::$app->session->set(self::place,"tontine");
        \Yii::$app->session->set(self::head,null);
    }

    public static function setChat() {
        \Yii::$app->session->set(self::place,"chat");
        \Yii::$app->session->set(self::head,null);
    }
    
    public static function setPay() {
        \Yii::$app->session->set(self::place,"pay");
        \Yii::$app->session->set(self::head,null);
    }

    public static function isHome() {
        return \Yii::$app->session->get(self::place) == "home" && \Yii::$app->session->get(self::head) == "home";
    }

    public static function isProfil() {
        return \Yii::$app->session->get(self::place) == "profil";
    }

    public static function isMembers() {
        return \Yii::$app->session->get(self::place) == "members";
    }

    public static function isAdministrators() {
        return \Yii::$app->session->get(self::place) == "administrators";
    }

    /** Cette méthode retourne true si l’action en cours est dette, ce qui permet de surligner le lien en bleu lorsque l’utilisateur est sur cette page.**/
    public static function isDette() {
        return \Yii::$app->controller->action->id === 'dette';
    }


    public static function isHelps() {
        return \Yii::$app->session->get(self::head) == "types-aide";
    }

    public static function isPayments(){
        return Yii::$app->controller->action->id == "payments";
    }

    public static function isDettes() {
        return \Yii::$app->session->get(self::place) == "dettes";
    }

    public static function setDettes() {
        \Yii::$app->session->set(self::place, "dettes");
        \Yii::$app->session->set(self::head, null);
    }

    public static function isAccueil() {
        return \Yii::$app->session->get(self::head) == "home";
    }
    public static function isEpargnes() {
        return \Yii::$app->session->get(self::head) == "epargnes";
    }

    public static function isEmprunts() {
        return \Yii::$app->session->get(self::head) == "emprunts";
    }

    public static function isContributions() {
        return \Yii::$app->session->get(self::head) == "contributions";
    }

    public static function isSessions() {
        return \Yii::$app->session->get(self::head) == "sessions";
    }

    public static function isExercices() {
        return \Yii::$app->session->get(self::head) == "exercises";
    }

    public static function isChat() {
        return \Yii::$app->session->get(self::place) == "chat";
    }

    public static function isAides() {
        return \Yii::$app->session->get(self::head) == "helps";
    }
 
    public static function isTypesAide() {
        return \Yii::$app->session->get(self::head) == "types-aide";
    }

    public static function isPay() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return 
            \Yii::$app->session->get(self::place) == "pay" || 
            \Yii::$app->session->get(self::head) == "pay" ||
            in_array($action, ['payer', 'pay']);
    }

    public static function isTontine(){
        return \Yii::$app->session->get(self::head) == "tontine";
    }
}