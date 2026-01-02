<?php
require(__DIR__ . '/vendor/autoload.php');
require(__DIR__ . '/vendor/yiisoft/yii2/Yii.php');
$config = require(__DIR__ . '/config/web.php');
new yii\web\Application($config);

$memberCount = (new \yii\db\Query())->from('member')->count();
$userCount = (new \yii\db\Query())->from('user')->count();
$adminCount = (new \yii\db\Query())->from('administrator')->count();

echo "Members: $memberCount\n";
echo "Users: $userCount\n";
echo "Admins: $adminCount\n";
