<?php
/**
 * Script pour exécuter toutes les migrations Yii2
 * Crée toutes les tables de l'application en une seule fois
 * URL: https://ihm-mutuelle-web.onrender.com/run-migrations.php
 */

// Désactiver la limite de temps
set_time_limit(300);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\console\Application($config);

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Migrations Yii2</title>";
echo "<style>body{font-family:monospace;padding:20px;background:#f5f5f5;}";
echo ".success{color:green;}.error{color:red;}.info{color:blue;}</style></head><body>";
echo "<h1>🔧 Exécution des Migrations Yii2</h1>";
echo "<pre>";

try {
    echo "<span class='info'>Démarrage des migrations...</span>\n\n";
    
    // Créer le composant de migration
    $migrationPath = Yii::getAlias('@app/migrations');
    
    // Exécuter la commande migrate
    $controller = new yii\console\controllers\MigrateController('migrate', $application);
    $controller->migrationPath = $migrationPath;
    $controller->interactive = false; // Mode non-interactif
    
    echo "<span class='info'>Chemin des migrations: $migrationPath</span>\n\n";
    
    // Exécuter toutes les migrations
    ob_start();
    $result = $controller->runAction('up');
    $output = ob_get_clean();
    
    echo $output;
    
    if ($result === 0) {
        echo "\n<span class='success'>✅ Toutes les migrations ont été exécutées avec succès!</span>\n";
    } else {
        echo "\n<span class='error'>⚠️ Certaines migrations ont échoué (code: $result)</span>\n";
    }
    
    echo "\n<h2>📊 Tables créées</h2>\n";
    
    // Lister toutes les tables
    $tables = Yii::$app->db->schema->getTableNames();
    echo "<span class='success'>Nombre de tables: " . count($tables) . "</span>\n\n";
    foreach ($tables as $table) {
        echo "  ✓ $table\n";
    }
    
    echo "\n<h2 style='color:green;'>🎉 Base de données initialisée avec succès!</h2>";
    echo "<p><a href='/direct-admin-login.php' style='font-size:18px;'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 class='error'>❌ Erreur lors de l'exécution des migrations</h2>";
    echo "<p class='error'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre class='error'>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

echo "</pre></body></html>";
