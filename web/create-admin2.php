<?php
/**
 * Création d'un compte admin alternatif pour test
 * URL: https://ihm-mutuelle-web.onrender.com/create-admin2.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Création d'un Compte Admin Alternatif</h1>";
echo "<pre>";

try {
    $db = Yii::$app->db;
    
    // Nouveau compte admin
    $login = 'superadmin';
    $password = 'Test@2026';
    
    echo "=== CRÉATION DU COMPTE ===\n";
    echo "Login: $login\n";
    echo "Mot de passe: $password\n\n";
    
    // Vérifier si l'utilisateur existe déjà
    $existingUser = \app\models\User::findOne(['login' => $login]);
    if ($existingUser) {
        echo "⚠️ L'utilisateur '$login' existe déjà. Suppression...\n";
        $db->createCommand()->delete('administrator', ['user_id' => $existingUser->id])->execute();
        $db->createCommand()->delete('user', ['id' => $existingUser->id])->execute();
        echo "✅ Ancien utilisateur supprimé\n\n";
    }
    
    // Générer le hash du mot de passe
    $passwordHash = Yii::$app->security->generatePasswordHash($password);
    echo "Hash généré: " . substr($passwordHash, 0, 60) . "...\n\n";
    
    // Créer l'utilisateur
    $db->createCommand()->insert('user', [
        'login' => $login,
        'password' => $passwordHash,
        'auth_key' => Yii::$app->security->generateRandomString(),
    ])->execute();
    
    $userId = $db->getLastInsertID();
    echo "✅ Utilisateur créé avec ID: $userId\n";
    
    // Créer l'administrateur
    $db->createCommand()->insert('administrator', [
        'user_id' => $userId,
        'name' => 'Super',
        'surname' => 'Admin',
    ])->execute();
    
    echo "✅ Administrateur créé\n\n";
    
    // Vérifier immédiatement
    echo "=== VÉRIFICATION ===\n";
    $user = \app\models\User::findOne(['login' => $login]);
    if ($user) {
        echo "✅ Utilisateur trouvé: ID={$user->id}, Login={$user->login}\n";
        
        $isValid = $user->validatePassword($password);
        echo "✅ Validation du mot de passe: " . ($isValid ? "VALIDE" : "INVALIDE") . "\n";
        
        $admin = \app\models\Administrator::findOne(['user_id' => $user->id]);
        if ($admin) {
            echo "✅ Statut administrateur confirmé: ID={$admin->id}\n";
        }
    }
    
    echo "\n<h2 style='color: green;'>🎉 Nouveau compte admin créé avec succès!</h2>";
    echo "<p><strong>Identifiants de connexion :</strong></p>";
    echo "<ul>";
    echo "<li><strong>Login :</strong> $login</li>";
    echo "<li><strong>Mot de passe :</strong> $password</li>";
    echo "</ul>";
    echo "<p><a href='/'>Tester la connexion</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
