<?php
/**
 * Création de la table exercise
 * URL: https://ihm-mutuelle-web.onrender.com/create-exercise-table.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Création de la table Exercise</h1><pre>";

try {
    $db = Yii::$app->db;
    
    echo "Création de la table 'exercise'...\n";
    $db->createCommand("
        CREATE TABLE IF NOT EXISTS exercise (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            start_date DATE,
            end_date DATE,
            active BOOLEAN DEFAULT true,
            interest DECIMAL(10,2),
            inscription_amount DECIMAL(10,2),
            social_crown_amount DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅ Table 'exercise' créée avec succès!\n\n";
    
    echo "<h2 style='color:green;'>🎉 Table exercise créée!</h2>";
    echo "<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
