<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';
new yii\web\Application($config);

// Récupérer l'utilisateur root
$sql = "SELECT u.id, u.name, u.password, a.username 
        FROM user u 
        JOIN administrator a ON u.id = a.user_id 
        WHERE a.username='root'";
$user = Yii::$app->db->createCommand($sql)->queryOne();

if ($user) {
    echo "Utilisateur trouvé:\n";
    echo "ID: " . $user['id'] . "\n";
    echo "Username: " . $user['username'] . "\n";
    echo "Password hash: " . $user['password'] . "\n\n";
    
    // Tester le mot de passe
    $testPassword = 'root';
    $isValid = Yii::$app->security->validatePassword($testPassword, $user['password']);
    
    echo "Test du mot de passe '$testPassword': " . ($isValid ? "✓ VALIDE" : "✗ INVALIDE") . "\n";
    
    // Tester d'autres mots de passe possibles
    $passwords = ['root', 'admin', 'password', ''];
    echo "\nTest de différents mots de passe:\n";
    foreach ($passwords as $pwd) {
        $valid = Yii::$app->security->validatePassword($pwd, $user['password']);
        echo "  '$pwd': " . ($valid ? "✓" : "✗") . "\n";
    }
} else {
    echo "Utilisateur 'root' non trouvé!\n";
}
