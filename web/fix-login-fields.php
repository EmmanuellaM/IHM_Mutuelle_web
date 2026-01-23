<?php
/**
 * Script pour corriger les champs login manquants
 * URL: https://ihm-mutuelle-web.onrender.com/fix-login-fields.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Correction des Champs Login Manquants</h1><pre>";

try {
    $db = Yii::$app->db;
    
    echo "=== CORRECTION DES ADMINISTRATEURS ===\n\n";
    
    // Récupérer tous les admins dont le user n'a pas de login
    $query = "
        SELECT u.id as user_id, u.login, a.username, a.id as admin_id
        FROM \"user\" u
        JOIN administrator a ON u.id = a.user_id
        WHERE u.login IS NULL OR u.login = ''
    ";
    
    $admins = $db->createCommand($query)->queryAll();
    
    foreach ($admins as $admin) {
        echo "Admin ID {$admin['admin_id']} (User ID {$admin['user_id']})\n";
        echo "  Username admin: {$admin['username']}\n";
        echo "  Login actuel: " . ($admin['login'] ?: '(vide)') . "\n";
        
        // Mettre à jour le login avec le username de l'admin
        $db->createCommand()->update('user', 
            ['login' => $admin['username']], 
            ['id' => $admin['user_id']]
        )->execute();
        
        echo "  ✅ Login mis à jour: {$admin['username']}\n\n";
    }
    
    echo "\n=== CORRECTION DES MEMBRES ===\n\n";
    
    // Récupérer tous les membres dont le user n'a pas de login
    $query = "
        SELECT u.id as user_id, u.login, m.username, m.id as member_id
        FROM \"user\" u
        JOIN member m ON u.id = m.user_id
        WHERE u.login IS NULL OR u.login = ''
    ";
    
    $members = $db->createCommand($query)->queryAll();
    
    foreach ($members as $member) {
        echo "Member ID {$member['member_id']} (User ID {$member['user_id']})\n";
        echo "  Username member: {$member['username']}\n";
        echo "  Login actuel: " . ($member['login'] ?: '(vide)') . "\n";
        
        // Mettre à jour le login avec le username du membre
        $db->createCommand()->update('user', 
            ['login' => $member['username']], 
            ['id' => $member['user_id']]
        )->execute();
        
        echo "  ✅ Login mis à jour: {$member['username']}\n\n";
    }
    
    echo "<h2 style='color:green;'>🎉 Tous les champs login ont été corrigés!</h2>";
    echo "<p><strong>Vous pouvez maintenant vous connecter avec tous les comptes créés.</strong></p>";
    echo "<p><a href='/'>➡️ Se connecter</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
