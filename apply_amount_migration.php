<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
new yii\console\Application($config);

echo "Applying migration: Make saving.amount SIGNED...\n";
try {
    Yii::$app->db->createCommand("ALTER TABLE saving MODIFY amount INT(11) DEFAULT NULL")->execute();
    echo "Migration successful.\n";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
