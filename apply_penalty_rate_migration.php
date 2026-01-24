<?php
// apply_penalty_rate_migration.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "Application de la migration penalty_rate...\n";

try {
    $sql = file_get_contents(__DIR__ . '/migrations/add_penalty_rate_to_exercise.sql');
    Yii::$app->db->createCommand($sql)->execute();
    echo "Migration réussie : Colonne penalty_rate ajoutée.\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "La colonne existe déjà (OK).\n";
    } else {
        echo "Erreur : " . $e->getMessage() . "\n";
    }
}
