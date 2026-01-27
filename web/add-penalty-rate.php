<?php
/**
 * Script to add missing penalty_rate column to exercise table
 * Run this once via web: /web/add-penalty-rate.php
 * Delete after use!
 */

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

echo "<h1>Adding penalty_rate column to exercise table</h1>";

try {
    $db = Yii::$app->db;
    
    // Check if column already exists
    $tableSchema = $db->getTableSchema('exercise');
    
    if ($tableSchema === null) {
        echo "<p style='color:red'>ERROR: Table 'exercise' does not exist!</p>";
        exit;
    }
    
    if (isset($tableSchema->columns['penalty_rate'])) {
        echo "<p style='color:green'>Column 'penalty_rate' already exists. No action needed.</p>";
    } else {
        // Add the column
        $db->createCommand("ALTER TABLE `exercise` ADD `penalty_rate` FLOAT NULL DEFAULT NULL")->execute();
        echo "<p style='color:green'>SUCCESS: Column 'penalty_rate' added to 'exercise' table!</p>";
    }
    
    // Also check for 'state' column in session table
    $sessionSchema = $db->getTableSchema('session');
    if ($sessionSchema && !isset($sessionSchema->columns['state'])) {
        $db->createCommand("ALTER TABLE `session` ADD `state` VARCHAR(20) NULL DEFAULT 'SAVING'")->execute();
        echo "<p style='color:green'>SUCCESS: Column 'state' added to 'session' table!</p>";
    } else if ($sessionSchema && isset($sessionSchema->columns['state'])) {
        echo "<p style='color:green'>Column 'state' already exists in 'session' table.</p>";
    }
    
    echo "<p><strong>Done! Please delete this file after use.</strong></p>";
    
} catch (Exception $e) {
    echo "<p style='color:red'>ERROR: " . htmlspecialchars($e->getMessage()) . "</p>";
}
