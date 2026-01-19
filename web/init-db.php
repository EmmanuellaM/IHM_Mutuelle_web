<?php
/**
 * Script d'initialisation de la base de données PostgreSQL
 * À exécuter une seule fois via le navigateur
 * URL: https://ihm-mutuelle-web.onrender.com/init-db.php
 */

// Charger Yii
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

// Charger la configuration
$config = require(__DIR__ . '/../config/web.php');

// Créer l'application
$application = new yii\web\Application($config);

// Récupérer la connexion DB
$db = Yii::$app->db;

echo "<h1>Initialisation de la Base de Données PostgreSQL</h1>";
echo "<pre>";

try {
    // Créer la table user
    echo "Création de la table 'user'...\n";
    $db->createCommand("
        CREATE TABLE IF NOT EXISTS \"user\" (
            id SERIAL PRIMARY KEY,
            login VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            auth_key VARCHAR(32),
            access_token VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ")->execute();
    echo "✅ Table 'user' créée avec succès!\n\n";

    // Créer la table administrator
    echo "Création de la table 'administrator'...\n";
    $db->createCommand("
        CREATE TABLE IF NOT EXISTS administrator (
            id SERIAL PRIMARY KEY,
            user_id INTEGER NOT NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            surname VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES \"user\"(id) ON DELETE CASCADE
        )
    ")->execute();
    echo "✅ Table 'administrator' créée avec succès!\n\n";

    // Insérer l'utilisateur admin
    echo "Création de l'utilisateur admin...\n";
    $db->createCommand("
        INSERT INTO \"user\" (login, password, auth_key) 
        VALUES (
            'admin',
            :password,
            'test100key'
        ) ON CONFLICT (login) DO NOTHING
    ", [
        ':password' => Yii::$app->security->generatePasswordHash('admin123')
    ])->execute();
    echo "✅ Utilisateur 'admin' créé avec succès!\n\n";

    // Insérer l'administrateur
    echo "Création de l'administrateur...\n";
    $db->createCommand("
        INSERT INTO administrator (user_id, name, surname)
        SELECT id, 'Admin', 'System'
        FROM \"user\"
        WHERE login = 'admin'
        ON CONFLICT (user_id) DO NOTHING
    ")->execute();
    echo "✅ Administrateur créé avec succès!\n\n";

    echo "<h2 style='color: green;'>🎉 Base de données initialisée avec succès!</h2>";
    echo "<p><strong>Identifiants de connexion :</strong></p>";
    echo "<ul>";
    echo "<li><strong>Login :</strong> admin</li>";
    echo "<li><strong>Mot de passe :</strong> admin123</li>";
    echo "</ul>";
    echo "<p><a href='/'>Retour à l'accueil</a></p>";

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Erreur lors de l'initialisation</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>Trace:</strong></p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre>";
