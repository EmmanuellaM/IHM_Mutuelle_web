<?php
/**
 * Script COMPLET pour recréer TOUTE la base de données
 * Basé sur la structure MySQL locale exportée
 * URL: https://ihm-mutuelle-web.onrender.com/recreate-complete-database.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Recréation COMPLÈTE de la Base de Données</h1><pre>";
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
    
    // TABLE USER
    echo "Création de 'user'... ";
    $db->createCommand("
        CREATE TABLE \"user\" (
            id SERIAL PRIMARY KEY,
            login VARCHAR(255) UNIQUE,
            name VARCHAR(255),
            first_name VARCHAR(255),
            tel VARCHAR(255),
            email VARCHAR(255),
            address VARCHAR(255),
            type VARCHAR(255),
            avatar VARCHAR(255),
            password VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE ADMINISTRATOR
    echo "Création de 'administrator'... ";
    $db->createCommand("
        CREATE TABLE administrator (
            id SERIAL PRIMARY KEY,
            user_id INTEGER UNIQUE,
            root BOOLEAN DEFAULT false,
            username VARCHAR(255) UNIQUE,
            active BOOLEAN DEFAULT true,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE MEMBER
    echo "Création de 'member'... ";
    $db->createCommand("
        CREATE TABLE member (
            id SERIAL PRIMARY KEY,
            user_id INTEGER UNIQUE,
            username VARCHAR(255) UNIQUE,
            active BOOLEAN DEFAULT true,
            social_crown INTEGER DEFAULT 0,
            inscription INTEGER DEFAULT 0,
            administrator_id INTEGER,
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE,
            FOREIGN KEY (administrator_id) REFERENCES administrator(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE EXERCISE
    echo "Création de 'exercise'... ";
    $db->createCommand("
        CREATE TABLE exercise (
            id SERIAL PRIMARY KEY,
            year VARCHAR(4),
            interest INTEGER NOT NULL DEFAULT 1,
            active BOOLEAN DEFAULT true,
            administrator_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            inscription_amount INTEGER NOT NULL DEFAULT 0,
            social_crown_amount INTEGER NOT NULL DEFAULT 0,
            FOREIGN KEY (administrator_id) REFERENCES administrator(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE SESSION
    echo "Création de 'session'... ";
    $db->createCommand("
        CREATE TABLE session (
            id SERIAL PRIMARY KEY,
            exercise_id INTEGER,
            date DATE NOT NULL UNIQUE,
            administrator_id INTEGER,
            state VARCHAR(255) DEFAULT 'SAVING',
            active BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (exercise_id) REFERENCES exercise(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE HELP_TYPE
    echo "Création de 'help_type'... ";
    $db->createCommand("
        CREATE TABLE help_type (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            amount INTEGER,
            active BOOLEAN DEFAULT true
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE HELP
    echo "Création de 'help'... ";
    $db->createCommand("
        CREATE TABLE help (
            id SERIAL PRIMARY KEY,
            limit_date TIMESTAMP,
            unit_amount INTEGER,
            amount INTEGER,
            \"contributedAmount\" INTEGER,
            comments TEXT,
            state BOOLEAN DEFAULT true,
            administrator_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            help_type_id INTEGER,
            member_id INTEGER,
            amount_from_social_fund DECIMAL(10,2) DEFAULT 0.00,
            FOREIGN KEY (administrator_id) REFERENCES administrator(id),
            FOREIGN KEY (help_type_id) REFERENCES help_type(id),
            FOREIGN KEY (member_id) REFERENCES member(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE SAVING
    echo "Création de 'saving'... ";
    $db->createCommand("
        CREATE TABLE saving (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            administrator_id INTEGER,
            amount INTEGER,
            \"EpargneCumul\" INTEGER NOT NULL,
            session_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id),
            FOREIGN KEY (session_id) REFERENCES session(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE BORROWING
    echo "Création de 'borrowing'... ";
    $db->createCommand("
        CREATE TABLE borrowing (
            id SERIAL PRIMARY KEY,
            interest INTEGER,
            amount INTEGER,
            member_id INTEGER,
            administrator_id INTEGER,
            session_id INTEGER,
            state BOOLEAN DEFAULT true,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id),
            FOREIGN KEY (session_id) REFERENCES session(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE BORROWING_SAVING
    echo "Création de 'borrowing_saving'... ";
    $db->createCommand("
        CREATE TABLE borrowing_saving (
            id SERIAL PRIMARY KEY,
            borrowing_id INTEGER,
            saving_id INTEGER,
            percent DOUBLE PRECISION,
            FOREIGN KEY (borrowing_id) REFERENCES borrowing(id),
            FOREIGN KEY (saving_id) REFERENCES saving(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE REFUND
    echo "Création de 'refund'... ";
    $db->createCommand("
        CREATE TABLE refund (
            id SERIAL PRIMARY KEY,
            amount DOUBLE PRECISION,
            borrowing_id INTEGER,
            member_id INTEGER,
            administrator_id INTEGER,
            exercise_id INTEGER,
            session_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (borrowing_id) REFERENCES borrowing(id),
            FOREIGN KEY (member_id) REFERENCES member(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id),
            FOREIGN KEY (exercise_id) REFERENCES exercise(id),
            FOREIGN KEY (session_id) REFERENCES session(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE CONTRIBUTION
    echo "Création de 'contribution'... ";
    $db->createCommand("
        CREATE TABLE contribution (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            date TIMESTAMP,
            state BOOLEAN DEFAULT false,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            help_id INTEGER,
            amount DECIMAL(18,2),
            administrator_id INTEGER,
            FOREIGN KEY (member_id) REFERENCES member(id),
            FOREIGN KEY (help_id) REFERENCES help(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE AGAPE
    echo "Création de 'agape'... ";
    $db->createCommand("
        CREATE TABLE agape (
            agape_id SERIAL PRIMARY KEY,
            amount INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            session_id INTEGER,
            FOREIGN KEY (session_id) REFERENCES session(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE TONTINE_TYPE
    echo "Création de 'tontine_type'... ";
    $db->createCommand("
        CREATE TABLE tontine_type (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255),
            amount INTEGER,
            active BOOLEAN DEFAULT true
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE TONTINE
    echo "Création de 'tontine'... ";
    $db->createCommand("
        CREATE TABLE tontine (
            id SERIAL PRIMARY KEY,
            limit_date TIMESTAMP,
            unit_amount INTEGER,
            amount INTEGER,
            comments TEXT,
            state BOOLEAN DEFAULT true,
            administrator_id INTEGER,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            tontine_type_id INTEGER,
            member_id INTEGER,
            FOREIGN KEY (administrator_id) REFERENCES administrator(id),
            FOREIGN KEY (tontine_type_id) REFERENCES tontine_type(id),
            FOREIGN KEY (member_id) REFERENCES member(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE CONTRIBUTION_TONTINE
    echo "Création de 'contribution_tontine'... ";
    $db->createCommand("
        CREATE TABLE contribution_tontine (
            id SERIAL PRIMARY KEY,
            member_id INTEGER,
            date TIMESTAMP,
            state BOOLEAN DEFAULT false,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            tontine_id INTEGER,
            administrator_id INTEGER,
            FOREIGN KEY (member_id) REFERENCES member(id),
            FOREIGN KEY (tontine_id) REFERENCES tontine(id),
            FOREIGN KEY (administrator_id) REFERENCES administrator(id)
        )
    ")->execute();
    echo "✅\n";
    
    // TABLE RENFLOUEMENT
    echo "Création de 'renflouement'... ";
    $db->createCommand("
        CREATE TABLE renflouement (
            id SERIAL PRIMARY KEY,
            member_id INTEGER NOT NULL,
            exercise_id INTEGER NOT NULL,
            next_exercise_id INTEGER NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            paid_amount DECIMAL(10,2) DEFAULT 0.00,
            status VARCHAR(255) DEFAULT 'en_attente',
            start_session_number INTEGER NOT NULL,
            created_at TIMESTAMP,
            updated_at TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE,
            FOREIGN KEY (exercise_id) REFERENCES exercise(id) ON DELETE CASCADE,
            FOREIGN KEY (next_exercise_id) REFERENCES exercise(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    // TABLES SUPPLÉMENTAIRES
    echo "Création de 'chat_message'... ";
    $db->createCommand("
        CREATE TABLE chat_message (
            id SERIAL PRIMARY KEY,
            sender_id INTEGER NOT NULL,
            message TEXT NOT NULL,
            created_at INTEGER NOT NULL,
            FOREIGN KEY (sender_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'chat_messages'... ";
    $db->createCommand("
        CREATE TABLE chat_messages (
            id SERIAL PRIMARY KEY,
            sender_id INTEGER NOT NULL,
            receiver_id INTEGER NOT NULL,
            message TEXT NOT NULL,
            created_at INTEGER NOT NULL,
            updated_at INTEGER,
            FOREIGN KEY (sender_id) REFERENCES \"user\"(id) ON DELETE CASCADE,
            FOREIGN KEY (receiver_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'payments'... ";
    $db->createCommand("
        CREATE TABLE payments (
            id SERIAL PRIMARY KEY,
            member_id INTEGER NOT NULL,
            payment_id VARCHAR(255) UNIQUE NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            payment_method VARCHAR(255) NOT NULL,
            transaction_id VARCHAR(255) UNIQUE NOT NULL,
            phone_number VARCHAR(255),
            status VARCHAR(255) NOT NULL DEFAULT 'completed',
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL,
            FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'registration'... ";
    $db->createCommand("
        CREATE TABLE registration (
            id SERIAL PRIMARY KEY,
            member_id INTEGER NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            registration_date DATE NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'social_fund'... ";
    $db->createCommand("
        CREATE TABLE social_fund (
            id SERIAL PRIMARY KEY,
            member_id INTEGER NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            contribution_date DATE NOT NULL,
            payment_method VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL DEFAULT 'pending',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'financial_aid'... ";
    $db->createCommand("
        CREATE TABLE financial_aid (
            id SERIAL PRIMARY KEY,
            member_id INTEGER NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (member_id) REFERENCES member(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅\n";
    
    echo "Création de 'migration'... ";
    $db->createCommand("
        CREATE TABLE migration (
            version VARCHAR(180) PRIMARY KEY,
            apply_time INTEGER
        )
    ")->execute();
    echo "✅\n";
    
    echo "\n=== INSERTION DES DONNÉES INITIALES ===\n\n";
    
    // Créer l'admin root
    $passwordHash = Yii::$app->security->generatePasswordHash('admin123');
    $db->createCommand()->insert('user', [
        'login' => 'root',
        'name' => 'root',
        'first_name' => 'root',
        'tel' => '00000',
        'email' => 'root@root.root',
        'type' => 'ADMINISTRATOR',
        'password' => $passwordHash,
        'created_at' => date('Y-m-d H:i:s'),
    ])->execute();
    
    $userId = $db->getLastInsertID();
    
    $db->createCommand()->insert('administrator', [
        'user_id' => $userId,
        'root' => true,
        'username' => 'root',
        'active' => true,
    ])->execute();
    
    echo "✅ Compte admin root créé : root / admin123\n\n";
    
    // Insérer les types d'aide
    $helpTypes = [
        ['Membre malade', 200000],
        ['Décès d\'un membre', 1000000],
        ['Décès du parent d\'un membre', 200000],
        ['Décès de l\'enfant d\'un membre', 500000],
        ['Mariage d\'un membre', 500000],
        ['Mariage dans la famille d\'un membre', 200000],
    ];
    
    foreach ($helpTypes as $helpType) {
        $db->createCommand()->insert('help_type', [
            'title' => $helpType[0],
            'amount' => $helpType[1],
            'active' => true,
        ])->execute();
    }
    
    echo "✅ Types d'aide insérés\n\n";
    
    echo "<h2 style='color:green;'>🎉 Base de données COMPLÈTE recréée avec succès!</h2>";
    echo "<p><strong>Toutes les tables et colonnes sont maintenant identiques à votre base locale !</strong></p>";
    echo "<p><a href='/'>➡️ Se connecter (root / admin123)</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
