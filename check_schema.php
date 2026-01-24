<?php
// check_schema.php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';
$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

echo "Table Saving Schema:\n";
$schema = Yii::$app->db->getTableSchema('saving');
foreach ($schema->columns as $column) {
    echo "- " . $column->name . " (" . $column->type . ")\n";
}
