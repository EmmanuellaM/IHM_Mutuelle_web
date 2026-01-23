<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

new yii\console\Application($config);

use app\models\User;

$user = new User();
echo "--- User Table Schema ---\n";
foreach ($user->attributes() as $attr) {
    echo $attr . "\n";
}

echo "\n--- First User Data ---\n";
$firstUser = User::find()->one();
if ($firstUser) {
    print_r($firstUser->attributes);
}
