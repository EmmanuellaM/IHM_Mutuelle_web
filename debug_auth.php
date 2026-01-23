<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/console.php';

new yii\console\Application($config);

use app\models\Administrator;
use app\models\User;

echo "--- Administrators with username 'root' ---\n";
$admins = Administrator::find()->where(['username' => 'root'])->all();
foreach ($admins as $admin) {
    echo "ID: " . $admin->id . ", UserID: " . $admin->user_id . ", Root: " . $admin->root . "\n";
    $user = User::findOne($admin->user_id);
    if ($user) {
        echo "  -> Linked User: ID: " . $user->id . ", Name: " . $user->name . ", Type: " . $user->type . "\n";
        echo "  -> Password Hash: " . $user->password . "\n";
        $valid = \Yii::$app->getSecurity()->validatePassword('root', $user->password);
        echo "  -> Password 'root' valid? " . ($valid ? "YES" : "NO") . "\n";
    } else {
        echo "  -> LINKED USER NOT FOUND!\n";
    }
}

echo "\n--- All Users with 'root' in name or email ---\n";
$users = User::find()->where(['like', 'name', 'root'])->orWhere(['like', 'email', 'root'])->all();
foreach ($users as $user) {
    echo "ID: " . $user->id . ", Name: " . $user->name . ", Email: " . $user->email . ", Type: " . $user->type . "\n";
}
