<?php
/**
 * Script de vérification de la base de données
 * URL: https://ihm-mutuelle-web.onrender.com/check-db.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Vérification de la Base de Données</h1>";
echo "<pre>";

try {
    $db = Yii::$app->db;
    
    // Vérifier les utilisateurs
    echo "=== UTILISATEURS ===\n";
    $users = $db->createCommand('SELECT id, login, created_at FROM "user"')->queryAll();
    if (empty($users)) {
        echo "❌ Aucun utilisateur trouvé!\n\n";
    } else {
        foreach ($users as $user) {
            echo "✅ ID: {$user['id']}, Login: {$user['login']}, Créé: {$user['created_at']}\n";
        }
        echo "\n";
    }
    
    // Vérifier les administrateurs
    echo "=== ADMINISTRATEURS ===\n";
    $admins = $db->createCommand('SELECT id, user_id, name, surname FROM administrator')->queryAll();
    if (empty($admins)) {
        echo "❌ Aucun administrateur trouvé!\n\n";
    } else {
        foreach ($admins as $admin) {
            echo "✅ ID: {$admin['id']}, User ID: {$admin['user_id']}, Nom: {$admin['name']} {$admin['surname']}\n";
        }
        echo "\n";
    }
    
    // Recréer l'utilisateur admin avec un nouveau hash
    echo "=== RECRÉATION DE L'ADMIN ===\n";
    
    // Supprimer l'ancien admin si existe
    $db->createCommand('DELETE FROM administrator WHERE user_id IN (SELECT id FROM "user" WHERE login = :login)', [':login' => 'admin'])->execute();
    $db->createCommand('DELETE FROM "user" WHERE login = :login', [':login' => 'admin'])->execute();
    echo "✅ Ancien admin supprimé\n";
    
    // Créer le nouveau hash
    $password = Yii::$app->security->generatePasswordHash('admin123');
    echo "✅ Nouveau hash généré: " . substr($password, 0, 50) . "...\n";
    
    // Insérer le nouvel utilisateur
    $db->createCommand()->insert('user', [
        'login' => 'admin',
        'password' => $password,
        'auth_key' => Yii::$app->security->generateRandomString(),
    ])->execute();
    $userId = $db->getLastInsertID();
    echo "✅ Utilisateur créé avec ID: $userId\n";
    
    // Insérer l'administrateur
    $db->createCommand()->insert('administrator', [
        'user_id' => $userId,
        'name' => 'Admin',
        'surname' => 'System',
    ])->execute();
    echo "✅ Administrateur créé\n\n";
    
    echo "<h2 style='color: green;'>🎉 Admin recréé avec succès!</h2>";
    echo "<p><strong>Identifiants :</strong></p>";
    echo "<ul>";
    echo "<li><strong>Login :</strong> admin</li>";
    echo "<li><strong>Mot de passe :</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='/'>Retour à l'accueil</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
