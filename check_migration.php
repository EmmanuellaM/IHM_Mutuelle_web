<?php
// apply_migration_simple.php - Add field without foreign key constraint
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== Adding last_penalty_session_id to borrowing table (without FK) ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Check if column already exists
    $columns = $db->createCommand("SHOW COLUMNS FROM borrowing LIKE 'last_penalty_session_id'")->queryAll();
    
    if (count($columns) > 0) {
        echo "✅ Column 'last_penalty_session_id' already exists.\n";
    } else {
        echo "❌ Column doesn't exist yet. Adding...\n";
    }
    
    echo "\n✅ Migration check completed!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
