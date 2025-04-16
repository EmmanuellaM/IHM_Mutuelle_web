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

    // Menu Accueil (navbar)
    public static function isHome() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'accueil');
    }

    // Profil
    public static function isProfil() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && in_array($action, ['profil', 'modifierprofil', 'enregistrermodifierprofil', 'modifiermotdepasse']));
    }

    public static function isMembers() {
        return \Yii::$app->session->get(self::place) == "members";
    }

    public static function isAdministrators() {
        return \Yii::$app->session->get(self::place) == "administrators";
    }

    /** Cette méthode retourne true si l’action en cours est dette, ce qui permet de surligner le lien en bleu lorsque l’utilisateur est sur cette page.**/
    // Sidebar : Ma dette
    public static function isDette() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'dette');
    }


    public static function isHelps() {
        return \Yii::$app->session->get(self::head) == "types-aide";
    }

    // Sidebar : Mes paiements
    public static function isPayments(){
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'payments');
    }

    public static function isDettes() {
        return \Yii::$app->session->get(self::place) == "dettes";
    }

    public static function setDettes() {
        \Yii::$app->session->set(self::place, "dettes");
        \Yii::$app->session->set(self::head, null);
    }
    
    public static function setDette() {
        \Yii::$app->session->set(self::place, "dette");
        \Yii::$app->session->set(self::head, null);
    }

    public static function isAccueil() {
        return \Yii::$app->session->get(self::head) == "home";
    }
    // Menu Mes épargnes (navbar)
    public static function isEpargnes() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'epargnes');
    }

    // Menu Mes emprunts (navbar)
    public static function isEmprunts() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'emprunts');
    }

    // Menu Mes contributions (navbar)
    public static function isContributions() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'contributions');
    }

    // Sidebar : Sessions
    public static function isSessions() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'sessions');
    }

    // Sidebar : Détails exercices
    public static function isExercices() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'exercises');
    }

    // Sidebar : Chat (uniquement si on est sur la page chat)
    public static function isChat() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'chat');
    }

    // Menu Aides (navbar)
    public static function isAides() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'aides');
    }
 
    // Sidebar : Type d'aides
    public static function isTypesAide() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && $action === 'typesaide');
    }

    // Sidebar : Payer
    // Sidebar : Payer (toutes les actions liées au paiement)
    public static function isPay() {
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return (
            $controller && $controller->id === 'member' && in_array($action, [
                'pay', 'success', 'error', 'validatemobilepayment', 'validate-mobile-payment'
            ])
        );
    }

    // Sidebar : Les Tontines
    public static function isTontine(){
        $controller = \Yii::$app->controller;
        $action = $controller ? $controller->action->id : null;
        return ($controller && $controller->id === 'member' && in_array($action, ['tontinetypes', 'nouvelletontine', 'ajoutertontine']));
    }
}