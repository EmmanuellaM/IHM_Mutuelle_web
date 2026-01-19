<?php
/**
 * Test final de connexion - simule exactement le formulaire
 * URL: https://ihm-mutuelle-web.onrender.com/final-test.php
 */

// Simuler une requête AJAX POST
$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'admin';
$_POST['password'] = 'admin123';
$_POST['remember'] = false;

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

// Appeler directement l'action
$controller = new \app\controllers\GuestController('guest', $application);
$result = $controller->actionAdministratorForm();

// Afficher le résultat
header('Content-Type: application/json');
echo json_encode($result, JSON_PRETTY_PRINT);
