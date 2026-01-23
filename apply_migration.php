<?php
// apply_migration.php - Script to add last_penalty_session_id field
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "=== Adding last_penalty_session_id to borrowing table ===\n\n";

try {
    $db = Yii::$app->db;
    
    // Check if column already exists
    $columns = $db->createCommand("SHOW COLUMNS FROM borrowing LIKE 'last_penalty_session_id'")->queryAll();
    
    if (count($columns) > 0) {
        echo "Column 'last_penalty_session_id' already exists.\n";
    } else {
        // Add the column
        $db->createCommand("
            ALTER TABLE borrowing 
            ADD COLUMN last_penalty_session_id INT NULL
        ")->execute();
        
        echo "✅ Column 'last_penalty_session_id' added successfully.\n";
        
        // Add foreign key
        $db->createCommand("
            ALTER TABLE borrowing
            ADD CONSTRAINT fk_borrowing_last_penalty_session 
                FOREIGN KEY (last_penalty_session_id) 
                REFERENCES session(id) 
                ON DELETE SET NULL
        ")->execute();
        
        echo "✅ Foreign key constraint added successfully.\n";
        
        // Add index
        $db->createCommand("
            CREATE INDEX idx_borrowing_last_penalty_session 
            ON borrowing(last_penalty_session_id)
        ")->execute();
        
        echo "✅ Index added successfully.\n";
    }
    
    echo "\n✅ Migration completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
