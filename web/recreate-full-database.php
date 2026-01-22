<?php
/**
 * Script pour recréer TOUTE la base de données depuis zéro
 * Supprime toutes les tables et les recrée avec la structure complète
 * URL: https://ihm-mutuelle-web.onrender.com/recreate-full-database.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Recréation Complète de la Base de Données</h1><pre>";
echo "<strong style='color:red;'>⚠️ ATTENTION : Ce script va SUPPRIMER toutes les tables existantes !</strong>\n\n";

try {
    $db = Yii::$app->db;
    
    // 1. Supprimer toutes les tables existantes
    echo "=== SUPPRESSION DES TABLES EXISTANTES ===\n\n";
    $tables = $db->schema->getTableNames();
    foreach ($tables as $table) {
        echo "Suppression de '$table'... ";
        $db->createCommand("DROP TABLE IF EXISTS \"$table\" CASCADE")->execute();
        echo "✅\n";
    }
    
    echo "\n=== CRÉATION DES TABLES AVEC STRUCTURE COMPLÈTE ===\n\n";
    
    // 2. Créer la table user avec TOUS les champs
    echo "Création de 'user'... ";
    $db->createCommand("
        CREATE TABLE \"user\" (
            id SERIAL PRIMARY KEY,
            login VARCHAR(255) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            auth_key VARCHAR(255),
            type VARCHAR(50) DEFAULT 'MEMBER',
            avatar VARCHAR(255),
            name VARCHAR(255),
            first_name VARCHAR(255),
            tel VARCHAR(50),
            email VARCHAR(255),
            address TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅\n";
    
    // 3. Créer la table administrator
    echo "Création de 'administrator'... ";
    $db->createCommand("
        CREATE TABLE administrator (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL UNIQUE,
            name VARCHAR(255),
            surname VARCHAR(255),
            username VARCHAR(255),
            root BOOLEAN DEFAULT false,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    // 4. Créer la table member
    echo "Création de 'member'... ";
    $db->createCommand("
        CREATE TABLE member (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL UNIQUE,
            inscription DECIMAL(10,2) DEFAULT 0,
            social_crown DECIMAL(10,2) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    // 5. Créer la table exercise
    echo "Création de 'exercise'... ";
    $db->createCommand("
        CREATE TABLE exercise (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255),
            year INTEGER,
            start_date DATE,
            end_date DATE,
            active BOOLEAN DEFAULT true,
            interest DECIMAL(10,2),
            inscription_amount DECIMAL(10,2),
            social_crown_amount DECIMAL(10,2),
            administrator_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅\n";
    
    // 6. Créer la table session
    echo "Création de 'session'... ";
    $db->createCommand("
        CREATE TABLE session (
            id SERIAL PRIMARY KEY,
            exercise_id INTEGER,
            administrator_id INTEGER,
            date DATE NOT NULL,
            active BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅\n";
    
    // 7. Créer les autres tables
    $otherTables = [
        'help' => "
            CREATE TABLE help (
                id SERIAL PRIMARY KEY,
                member_id INTEGER,
                help_type_id INTEGER,
                amount DECIMAL(10,2),
                amount_from_social_fund DECIMAL(10,2) DEFAULT 0,
                contributed_amount DECIMAL(10,2) DEFAULT 0,
                state BOOLEAN DEFAULT true,
                reason TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'help_type' => "
            CREATE TABLE help_type (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'agape' => "
            CREATE TABLE agape (
                id SERIAL PRIMARY KEY,
                amount DECIMAL(10,2),
                reason TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'saving' => "
            CREATE TABLE saving (
                id SERIAL PRIMARY KEY,
                member_id INTEGER,
                session_id INTEGER,
                amount DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'borrowing' => "
            CREATE TABLE borrowing (
                id SERIAL PRIMARY KEY,
                member_id INTEGER,
                amount DECIMAL(10,2),
                interest_rate DECIMAL(5,2),
                state BOOLEAN DEFAULT true,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'refund' => "
            CREATE TABLE refund (
                id SERIAL PRIMARY KEY,
                borrowing_id INTEGER,
                amount DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'contribution' => "
            CREATE TABLE contribution (
                id SERIAL PRIMARY KEY,
                member_id INTEGER,
                session_id INTEGER,
                amount DECIMAL(10,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'tontine' => "
            CREATE TABLE tontine (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255),
                amount DECIMAL(10,2),
                state BOOLEAN DEFAULT true,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
        'setting' => "
            CREATE TABLE setting (
                id SERIAL PRIMARY KEY,
                key VARCHAR(255) UNIQUE,
                value TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )"
    ];
    
    foreach ($otherTables as $tableName => $sql) {
        echo "Création de '$tableName'... ";
        $db->createCommand($sql)->execute();
        echo "✅\n";
    }
    
    echo "\n=== CRÉATION DU COMPTE ADMIN ===\n\n";
    
    // Créer l'admin
    $passwordHash = Yii::$app->security->generatePasswordHash('admin123');
    $db->createCommand()->insert('user', [
        'login' => 'admin',
        'password' => $passwordHash,
        'type' => 'ADMINISTRATOR',
        'email' => 'admin@mutuelle.com',
        'created_at' => date('Y-m-d H:i:s'),
    ])->execute();
    
    $userId = $db->getLastInsertID();
    
    $db->createCommand()->insert('administrator', [
        'user_id' => $userId,
        'username' => 'admin',
        'name' => 'Admin',
        'surname' => 'System',
        'root' => true,
        'created_at' => date('Y-m-d H:i:s'),
    ])->execute();
    
    echo "✅ Compte admin créé : admin / admin123\n\n";
    
    echo "<h2 style='color:green;'>🎉 Base de données recréée avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
