<?php
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

$sql = file_get_contents(__DIR__ . '/reset_database.sql');
try {
    // Split SQL by semicolon to execute statements individually if needed, 
    // but typically execute() handles multiple statements if driver allows.
    // To be safe/standard with Yii2 command, we can try executing the whole block.
    Yii::$app->db->createCommand($sql)->execute();
    echo "Database reset successfully (including member payments).\n";
} catch (\Exception $e) {
    echo "Error resetting database: " . $e->getMessage() . "\n";
}
