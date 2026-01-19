<?php
/**
 * Script pour créer toutes les tables manquantes
 * URL: https://ihm-mutuelle-web.onrender.com/create-all-tables.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Création des Tables de l'Application</h1>";
echo "<pre>";

try {
    $db = Yii::$app->db;
    
    echo "=== CRÉATION DES TABLES ===\n\n";
    
    // Table session
    echo "Création de la table 'session'...\n";
    $db->createCommand("
        CREATE TABLE IF NOT EXISTS session (
            id SERIAL PRIMARY KEY,
            date DATE NOT NULL,
            active BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅ Table 'session' créée\n\n";
    
    // Table member
    echo "Création de la table 'member'...\n";
    $db->createCommand("
        CREATE TABLE IF NOT EXISTS member (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            surname VARCHAR(255),
            phone VARCHAR(50),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅ Table 'member' créée\n\n";
    
    // Vous pouvez ajouter d'autres tables ici selon vos besoins
    
    echo "<h2 style='color: green;'>🎉 Tables créées avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php'>Retour à la connexion</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
