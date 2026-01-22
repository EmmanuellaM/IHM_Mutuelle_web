<?php
/**
 * Vérification des colonnes de la table user
 * URL: https://ihm-mutuelle-web.onrender.com/check-user-columns.php
 */

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
$application = new yii\web\Application($config);

echo "<h1>Vérification des Colonnes de la Table User</h1><pre>";

try {
    $db = Yii::$app->db;
    
    // Récupérer les colonnes de la table user
    $columns = $db->getTableSchema('user')->columns;
    
    echo "Colonnes existantes dans la table 'user' :\n\n";
    foreach ($columns as $column) {
        echo "  ✓ {$column->name} ({$column->type})\n";
    }
    
    echo "\n\nColonnes manquantes à ajouter :\n";
    $requiredColumns = ['id', 'login', 'password', 'auth_key', 'type', 'avatar', 'name', 'first_name', 'tel', 'email', 'address'];
    $existingColumnNames = array_keys($columns);
    
    $missing = array_diff($requiredColumns, $existingColumnNames);
    
    if (empty($missing)) {
        echo "  ✅ Aucune colonne manquante!\n";
    } else {
        foreach ($missing as $col) {
            echo "  ❌ $col\n";
        }
        
        echo "\n\n<h2>Ajout des colonnes manquantes...</h2>\n";
        
        // Ajouter les colonnes une par une
        foreach ($missing as $col) {
            try {
                echo "Ajout de '$col'... ";
                switch ($col) {
                    case 'name':
                    case 'first_name':
                    case 'tel':
                    case 'email':
                        $db->createCommand("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS $col VARCHAR(255)")->execute();
                        break;
                    case 'address':
                        $db->createCommand("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS $col TEXT")->execute();
                        break;
                    case 'type':
                        $db->createCommand("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS $col VARCHAR(50) DEFAULT 'MEMBER'")->execute();
                        break;
                    case 'avatar':
                        $db->createCommand("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS $col VARCHAR(255)")->execute();
                        break;
                    case 'auth_key':
                        $db->createCommand("ALTER TABLE \"user\" ADD COLUMN IF NOT EXISTS $col VARCHAR(255)")->execute();
                        break;
                }
                echo "✅\n";
            } catch (Exception $e) {
                echo "❌ Erreur: " . $e->getMessage() . "\n";
            }
        }
    }
    
    echo "\n<p><a href='/direct-admin-login.php'>➡️ Se connecter en tant qu'admin</a></p>";
    
} catch (Exception $e) {
    echo "<h2 style='color:red;'>❌ Erreur</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</pre>";
