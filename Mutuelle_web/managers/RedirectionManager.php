<?php
/**
 * Created by PhpStorm.
 * User: medric
 * Date: 27/12/18
 * Time: 19:21
 */

namespace app\managers;


use yii\web\Controller;

class RedirectionManager
{

    public static function abort(Controller $controller) {
        \Yii::$app->response->setStatusCode(404);
        $controller->layout = "404";
        return $controller->renderContent("<div class='container mt-5 text-center'><h1 class='text-danger'>Une erreur est survenue</h1><p class='lead'>Désolé, la page que vous recherchez est introuvable ou une erreur s'est produite lors du traitement.</p><a href='/guest/accueil' class='btn btn-primary'>Retour à l'accueil</a></div>");
    }
}