<?php
/**
 * Création de TOUTES les tables principales en une seule fois
 * URL: https://ihm-mutuelle-web.onrender.com/create-all-tables-final.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Création de TOUTES les Tables</h1><pre>";

try {
    $db = Yii::$app->db;
    
    // Liste des tables à créer
    $tables = [
        'help' => "CREATE TABLE IF NOT EXISTS help (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            amount DECIMAL(10,2),
            amount_from_social_fund DECIMAL(10,2) DEFAULT 0,
            reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'agape' => "CREATE TABLE IF NOT EXISTS agape (
            id SERIAL PRIMARY KEY,
            amount DECIMAL(10,2),
            reason TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'saving' => "CREATE TABLE IF NOT EXISTS saving (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            session_id INTEGER,
            amount DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'borrowing' => "CREATE TABLE IF NOT EXISTS borrowing (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            amount DECIMAL(10,2),
            interest_rate DECIMAL(5,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'refund' => "CREATE TABLE IF NOT EXISTS refund (
            id SERIAL PRIMARY KEY,
            borrowing_id INTEGER,
            amount DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'contribution' => "CREATE TABLE IF NOT EXISTS contribution (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            session_id INTEGER,
            amount DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'tontine' => "CREATE TABLE IF NOT EXISTS tontine (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            amount DECIMAL(10,2),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )",
        'setting' => "CREATE TABLE IF NOT EXISTS setting (
            id SERIAL PRIMARY KEY,
            key VARCHAR(255) UNIQUE,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $tableName => $sql) {
        echo "Création de la table '$tableName'...\n";
        try {
            $db->createCommand($sql)->execute();
            echo "✅ Table '$tableName' créée!\n";
        } catch (Exception $e) {
            echo "⚠️ Table '$tableName' existe déjà ou erreur: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n<h2 style='color:green;'>🎉 Tables créées avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
