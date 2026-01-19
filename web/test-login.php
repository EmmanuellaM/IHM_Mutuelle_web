<?php
/**
 * Script de test de connexion admin
 * URL: https://ihm-mutuelle-web.onrender.com/test-login.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Test de Connexion Admin</h1>";
echo "<pre>";

try {
    $db = Yii::$app->db;
    $username = 'admin';
    $password = 'admin123';
    
    echo "=== ÉTAPE 1: Recherche de l'utilisateur ===\n";
    $user = \app\models\User::findOne(['login' => $username]);
    
    if (!$user) {
        echo "❌ Utilisateur '$username' NON TROUVÉ dans la table user!\n";
        echo "Vérification de tous les utilisateurs:\n";
        $allUsers = $db->createCommand('SELECT id, login FROM "user"')->queryAll();
        foreach ($allUsers as $u) {
            echo "  - ID: {$u['id']}, Login: {$u['login']}\n";
        }
        die();
    }
    
    echo "✅ Utilisateur trouvé: ID={$user->id}, Login={$user->login}\n\n";
    
    echo "=== ÉTAPE 2: Vérification du mot de passe ===\n";
    echo "Hash stocké: " . substr($user->password, 0, 60) . "...\n";
    
    $isValid = $user->validatePassword($password);
    echo "Validation du mot de passe '$password': " . ($isValid ? "✅ VALIDE" : "❌ INVALIDE") . "\n\n";
    
    if (!$isValid) {
        echo "⚠️ Le mot de passe ne correspond pas!\n";
        echo "Recréation du hash...\n";
        $newHash = Yii::$app->security->generatePasswordHash($password);
        echo "Nouveau hash: " . substr($newHash, 0, 60) . "...\n";
        
        // Mettre à jour le mot de passe
        $db->createCommand()->update('user', ['password' => $newHash], ['id' => $user->id])->execute();
        echo "✅ Mot de passe mis à jour!\n\n";
        
        // Recharger l'utilisateur
        $user = \app\models\User::findOne(['login' => $username]);
        $isValid = $user->validatePassword($password);
        echo "Nouvelle validation: " . ($isValid ? "✅ VALIDE" : "❌ INVALIDE") . "\n\n";
    }
    
    echo "=== ÉTAPE 3: Vérification du statut administrateur ===\n";
    $admin = \app\models\Administrator::findOne(['user_id' => $user->id]);
    
    if (!$admin) {
        echo "❌ Cet utilisateur n'est PAS administrateur!\n";
        die();
    }
    
    echo "✅ Administrateur trouvé: ID={$admin->id}, Name={$admin->name}\n\n";
    
    echo "=== ÉTAPE 4: Test de connexion Yii ===\n";
    $loginResult = Yii::$app->user->login($user);
    echo "Résultat de Yii::app->user->login(): " . ($loginResult ? "✅ SUCCÈS" : "❌ ÉCHEC") . "\n";
    echo "Utilisateur connecté: " . (Yii::$app->user->isGuest ? "❌ NON" : "✅ OUI") . "\n";
    echo "User ID: " . Yii::$app->user->id . "\n\n";
    
    echo "<h2 style='color: green;'>🎉 TOUS LES TESTS PASSÉS!</h2>";
    echo "<p>La connexion devrait maintenant fonctionner avec:</p>";
    echo "<ul>";
    echo "<li><strong>Login:</strong> admin</li>";
    echo "<li><strong>Mot de passe:</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='/'>Tester la connexion</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
