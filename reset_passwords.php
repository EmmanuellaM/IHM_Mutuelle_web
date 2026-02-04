<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';

$config = require 'config/web.php';
new yii\web\Application($config);

// Réinitialiser le mot de passe de l'utilisateur admin (ID 8)
$user = app\models\User::findOne(8);
if ($user) {
    $user->password = Yii::$app->security->generatePasswordHash('admin123');
    $user->save(false);
    echo "✓ Mot de passe de 'admin' réinitialisé à 'admin123'\n";
} else {
    echo "✗ Utilisateur admin (ID 8) non trouvé\n";
}

// Aussi réinitialiser root
$userRoot = app\models\User::findOne(1);
if ($userRoot) {
    $userRoot->password = Yii::$app->security->generatePasswordHash('root');
    $userRoot->save(false);
    echo "✓ Mot de passe de 'root' réinitialisé à 'root'\n";
}
