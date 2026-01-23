<?php
/**
 * Script de diagnostic pour vérifier les données des administrateurs
 * URL: https://ihm-mutuelle-web.onrender.com/check-admins.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Diagnostic des Administrateurs</h1><pre>";

try {
    $db = Yii::$app->db;
    
    echo "=== LISTE DES UTILISATEURS ===\n\n";
    $users = $db->createCommand("SELECT id, login, name, first_name, email, type FROM \"user\" ORDER BY id")->queryAll();
    
    foreach ($users as $user) {
        echo "User ID: {$user['id']}\n";
        echo "  Login: {$user['login']}\n";
        echo "  Nom: {$user['name']} {$user['first_name']}\n";
        echo "  Email: {$user['email']}\n";
        echo "  Type: {$user['type']}\n\n";
    }
    
    echo "\n=== LISTE DES ADMINISTRATEURS ===\n\n";
    $admins = $db->createCommand("SELECT id, user_id, username, root, active FROM administrator ORDER BY id")->queryAll();
    
    foreach ($admins as $admin) {
        echo "Admin ID: {$admin['id']}\n";
        echo "  User ID: {$admin['user_id']}\n";
        echo "  Username: {$admin['username']}\n";
        echo "  Root: " . ($admin['root'] ? 'Oui' : 'Non') . "\n";
        echo "  Active: " . ($admin['active'] ? 'Oui' : 'Non') . "\n\n";
    }
    
    echo "\n=== CORRESPONDANCE USER <-> ADMINISTRATOR ===\n\n";
    $query = "
        SELECT 
            u.id as user_id,
            u.login,
            u.name,
            u.type,
            a.id as admin_id,
            a.username,
            a.root
        FROM \"user\" u
        LEFT JOIN administrator a ON u.id = a.user_id
        WHERE u.type = 'ADMINISTRATOR'
        ORDER BY u.id
    ";
    
    $results = $db->createCommand($query)->queryAll();
    
    foreach ($results as $row) {
        echo "User: {$row['login']} (ID: {$row['user_id']})\n";
        echo "  Nom: {$row['name']}\n";
        if ($row['admin_id']) {
            echo "  ✅ Lié à Administrator ID: {$row['admin_id']}\n";
            echo "  Username admin: {$row['username']}\n";
            echo "  Root: " . ($row['root'] ? 'Oui' : 'Non') . "\n";
        } else {
            echo "  ❌ PAS DE LIEN avec la table administrator !\n";
        }
        echo "\n";
    }
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
