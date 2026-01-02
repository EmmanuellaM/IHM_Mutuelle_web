<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

$db = Yii::$app->db;
$hashedPassword = Yii::$app->security->generatePasswordHash('root');

// Mettre à jour le mot de passe de l'utilisateur
$db->createCommand()->update('user', ['password' => $hashedPassword], ['id' => 2])->execute();

// Mettre à jour le username de l'administrateur
$db->createCommand()->update('administrator', ['username' => 'root'], ['user_id' => 2])->execute();

echo "✅ Identifiants mis à jour :\n";
echo "Username: root\n";
echo "Password: root\n";
?>
