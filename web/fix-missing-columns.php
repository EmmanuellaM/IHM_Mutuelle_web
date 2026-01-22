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
    
    echo "Ajout de la colonne 'social_crown' à la table 'member'...\n";
    $db->createCommand("
        ALTER TABLE member 
        ADD COLUMN IF NOT EXISTS social_crown DECIMAL(10,2) DEFAULT 0
    ")->execute();
    echo "✅ Colonne 'social_crown' ajoutée!\n\n";
    
    echo "Ajout de la colonne 'state' à la table 'help'...\n";
    $db->createCommand("
        ALTER TABLE help 
        ADD COLUMN IF NOT EXISTS state BOOLEAN DEFAULT true
    ")->execute();
    echo "✅ Colonne 'state' ajoutée!\n\n";
    
    echo "Ajout de la colonne 'type' à la table 'user'...\n";
    $db->createCommand("
        ALTER TABLE \"user\" 
        ADD COLUMN IF NOT EXISTS type VARCHAR(50) DEFAULT 'MEMBER'
    ")->execute();
    echo "✅ Colonne 'type' ajoutée!\n\n";
    
    echo "Ajout de la colonne 'avatar' à la table 'user'...\n";
    $db->createCommand("
        ALTER TABLE \"user\" 
        ADD COLUMN IF NOT EXISTS avatar VARCHAR(255)
    ")->execute();
    echo "✅ Colonne 'avatar' ajoutée!\n\n";
    
    echo "Ajout des colonnes de profil à la table 'user'...\n";
    $db->createCommand("
        ALTER TABLE \"user\" 
        ADD COLUMN IF NOT EXISTS name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS first_name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS tel VARCHAR(50),
        ADD COLUMN IF NOT EXISTS email VARCHAR(255),
        ADD COLUMN IF NOT EXISTS address TEXT
    ")->execute();
    echo "✅ Colonnes 'name', 'first_name', 'tel', 'email', 'address' ajoutées à user!\n\n";
    
    echo "Ajout des colonnes à la table 'administrator'...\n";
    $db->createCommand("
        ALTER TABLE administrator 
        ADD COLUMN IF NOT EXISTS name VARCHAR(255),
        ADD COLUMN IF NOT EXISTS surname VARCHAR(255),
        ADD COLUMN IF NOT EXISTS username VARCHAR(255),
        ADD COLUMN IF NOT EXISTS root BOOLEAN DEFAULT false
    ")->execute();
    echo "✅ Colonnes 'name', 'surname', 'username', 'root' ajoutées à administrator!\n\n";
    
    echo "Ajout de la colonne 'state' à la table 'tontine'...\n";
    $db->createCommand("
        ALTER TABLE tontine 
        ADD COLUMN IF NOT EXISTS state BOOLEAN DEFAULT true
    ")->execute();
    echo "✅ Colonne 'state' ajoutée à tontine!\n\n";
    
    echo "<h2 style='color:green;'>🎉 Colonnes ajoutées avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
