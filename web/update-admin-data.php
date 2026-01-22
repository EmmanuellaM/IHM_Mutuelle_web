<?php
/**
 * Mise à jour des données des administrateurs
 * URL: https://ihm-mutuelle-web.onrender.com/update-admin-data.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Mise à Jour des Données Administrateurs</h1><pre>";

try {
    $db = Yii::$app->db;
    
    // Récupérer tous les administrateurs
    $administrators = \app\models\Administrator::find()->all();
    
    echo "Mise à jour de " . count($administrators) . " administrateur(s)...\n\n";
    
    foreach ($administrators as $admin) {
        $user = $admin->user();
        
        echo "Admin ID {$admin->id}:\n";
        echo "  User login: {$user->login}\n";
        
        // Mettre à jour les champs de l'administrateur
        $admin->username = $user->login;
        $admin->name = $admin->name ?: 'Admin';
        $admin->surname = $admin->surname ?: 'System';
        
        if ($admin->save()) {
            echo "  ✅ Mis à jour: username={$admin->username}, name={$admin->name}, surname={$admin->surname}\n\n";
        } else {
            echo "  ❌ Erreur lors de la sauvegarde\n\n";
        }
    }
    
    echo "<h2 style='color:green;'>🎉 Données mises à jour avec succès!</h2>";
    echo "<p><a href='/administrator/administrateurs'>➡️ Voir la liste des administrateurs</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
