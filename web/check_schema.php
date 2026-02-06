<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/../config/web.php';
new yii\web\Application($config);

$schema = Yii::$app->db->getTableSchema('exercise');
echo "Columns in exercise table:\n";
foreach ($schema->columnNames as $col) {
    echo "- $col\n";
}
echo "\nChecking 'penalty_rate': ";
if (in_array('penalty_rate', $schema->columnNames)) {
    echo "EXISTS ✅\n";
} else {
    echo "MISSING ❌\n";
}
