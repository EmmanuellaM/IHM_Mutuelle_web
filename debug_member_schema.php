<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
new yii\console\Application($config);

$schema = Yii::$app->db->getTableSchema('member');
echo "Columns in 'member' table:\n";
foreach ($schema->columns as $column) {
    echo "- " . $column->name . "\n";
}
