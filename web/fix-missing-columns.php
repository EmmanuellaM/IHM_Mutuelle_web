<?php
/**
 * Ajout des colonnes manquantes aux tables
 * URL: https://ihm-mutuelle-web.onrender.com/fix-missing-columns.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Ajout des Colonnes Manquantes</h1><pre>";

try {
    $db = Yii::$app->db;
    
    echo "Ajout de la colonne 'inscription' à la table 'member'...\n";
    $db->createCommand("
        ALTER TABLE member 
        ADD COLUMN IF NOT EXISTS inscription DECIMAL(10,2) DEFAULT 0
    ")->execute();
    echo "✅ Colonne 'inscription' ajoutée!\n\n";
    
    echo "Ajout d'autres colonnes potentiellement manquantes...\n";
    $db->createCommand("
        ALTER TABLE member 
        ADD COLUMN IF NOT EXISTS social_crown DECIMAL(10,2) DEFAULT 0
    ")->execute();
    echo "✅ Colonne 'social_crown' ajoutée!\n\n";
    
    echo "<h2 style='color:green;'>🎉 Colonnes ajoutées avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
