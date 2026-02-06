<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Use console config to avoid web request issues
$config = require __DIR__ . '/config/console.php';

new yii\console\Application($config);

echo "Checking 'exercise' table schema...\n";

try {
    $schema = Yii::$app->db->getTableSchema('exercise');
    if (!$schema) {
        echo "Table 'exercise' definition not found via getTableSchema (might be caching or missing).\n";
        exit(1);
    }
    
    echo "Columns found:\n";
    $columns = $schema->columnNames;
    print_r($columns);
    
    if (in_array('penalty_rate', $columns)) {
        echo "\n✅ 'penalty_rate' column EXISTS.\n";
    } else {
        echo "\n❌ 'penalty_rate' column is MISSING.\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
