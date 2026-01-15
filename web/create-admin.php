<?php
/**
 * Script d'initialisation d'un administrateur pour Railway
 * À exécuter une seule fois via le navigateur
 */

// Charger Yii
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$app = new yii\web\Application($config);

echo "<!DOCTYPE html><html><head><title>Création Admin</title>";
echo "<style>body{font-family:sans-serif;padding:20px;max-width:600px;margin:0 auto;} .success{color:green;font-weight:bold;} .error{color:red;font-weight:bold;}</style>";
echo "</head><body>";
echo "<h1>🔐 Création Administrateur Railway</h1>";

try {
    // Vérifier la connexion à la base de données
    Yii::$app->db->open();
    echo "<p class='success'>✅ Connexion à la base de données réussie</p>";
    
    // Créer un utilisateur
    $user = new \app\models\User();
    $user->login = 'railway_admin';
    $user->email = 'railway@admin.com';
    $user->password = Yii::$app->security->generatePasswordHash('Railway2026!');
    $user->status = 10; // Actif
    
    if ($user->save()) {
        echo "<p class='success'>✅ Utilisateur créé (ID: {$user->id})</p>";
        
        // Créer l'administrateur
        $admin = new \app\models\Administrator();
        $admin->user_id = $user->id;
        
        if ($admin->save()) {
            echo "<p class='success'>✅ Administrateur créé avec succès !</p>";
            echo "<hr>";
            echo "<h2>🎉 Identifiants de connexion :</h2>";
            echo "<ul>";
            echo "<li><strong>Login :</strong> <code>railway_admin</code></li>";
            echo "<li><strong>Mot de passe :</strong> <code>Railway2026!</code></li>";
            echo "</ul>";
            echo "<p><a href='/guest/connexion' style='display:inline-block;padding:10px 20px;background:#2a5298;color:white;text-decoration:none;border-radius:5px;'>Aller à la page de connexion</a></p>";
        } else {
            echo "<p class='error'>❌ Erreur création admin: " . json_encode($admin->errors) . "</p>";
        }
    } else {
        echo "<p class='error'>❌ Erreur création utilisateur: " . json_encode($user->errors) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p class='error'>❌ Erreur: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "<hr><p><small>⚠️ Supprimez ce fichier après utilisation pour des raisons de sécurité.</small></p>";
echo "</body></html>";
