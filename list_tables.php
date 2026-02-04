<?php
// list_tables.php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

$db = Yii::$app->db;
echo "Connection String: " . $db->dsn . "\n";
echo "Username: " . $db->username . "\n";

try {
    $tables = $db->createCommand("SHOW TABLES")->queryColumn();
    echo "Tables in database:\n";
    foreach ($tables as $table) {
        echo " - $table\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
