<?php
// update_schema_borrowing.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== Updating Schema for New Borrowing Logic ===\n\n";

try {
    $db = Yii::$app->db;
    
    // 1. Add penalty_rate to exercise table
    $columns = $db->createCommand("SHOW COLUMNS FROM exercise LIKE 'penalty_rate'")->queryAll();
    if (count($columns) > 0) {
        echo "Column 'penalty_rate' already exists in 'exercise'.\n";
    } else {
        $db->createCommand("
            ALTER TABLE exercise 
            ADD COLUMN penalty_rate FLOAT DEFAULT 0 COMMENT 'Taux de pénalité (%)'
        ")->execute();
        echo "✅ Column 'penalty_rate' added to 'exercise'.\n";
    }
    
    // 2. Add is_insolvent to member table
    $columns = $db->createCommand("SHOW COLUMNS FROM member LIKE 'insoluble'")->queryAll();
    if (count($columns) > 0) {
        echo "Column 'insoluble' already exists in 'member'.\n";
    } else {
        $db->createCommand("
            ALTER TABLE member 
            ADD COLUMN insoluble BOOLEAN DEFAULT FALSE COMMENT 'Est insolvable'
        ")->execute();
        echo "✅ Column 'insoluble' added to 'member'.\n";
    }

    echo "\n✅ Schema update completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
