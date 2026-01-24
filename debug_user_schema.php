<?php
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';
$application = new yii\console\Application($config);

$schema = Yii::$app->db->getTableSchema('user');
echo "Columns in 'user' table:\n";
foreach ($schema->columns as $column) {
    echo "- " . $column->name . " (" . $column->type . ")\n";
}

echo "\nCheck if findByUsername exists in User class:\n";
$methods = get_class_methods(\app\models\User::class);
if (in_array('findByUsername', $methods)) {
    echo "User::findByUsername EXISTS.\n";
} else {
    echo "User::findByUsername does NOT exist.\n";
}
