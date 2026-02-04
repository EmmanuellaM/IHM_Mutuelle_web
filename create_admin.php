<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

// Créer un nouvel utilisateur
$user = new app\models\User();
$user->name = 'admin';
$user->first_name = 'Admin';
$user->email = 'admin@test.com';
$user->type = 'ADMINISTRATOR';
$user->password = Yii::$app->security->generatePasswordHash('admin123');
$user->save(false);

echo "Utilisateur créé avec ID: " . $user->id . "\n";

// Créer l'administrateur
$admin = new app\models\Administrator();
$admin->user_id = $user->id;
$admin->username = 'admin';
$admin->root = 0;
$admin->active = 1;
$admin->save(false);

echo "Administrateur créé avec ID: " . $admin->id . "\n";
echo "\n=== Nouvel administrateur créé avec succès ===\n";
echo "Username: admin\n";
echo "Password: admin123\n";
