<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

// Générer le hash pour le mot de passe "root"
$password = 'root';
$hash = Yii::$app->security->generatePasswordHash($password);

// Mettre à jour le mot de passe dans la base de données
$sql = "UPDATE user SET password = :password WHERE id = 1";
Yii::$app->db->createCommand($sql, [':password' => $hash])->execute();

echo "Mot de passe de l'administrateur 'root' réinitialisé à 'root'\n";
echo "Hash généré: $hash\n";
