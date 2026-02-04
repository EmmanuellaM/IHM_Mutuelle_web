<?php
require 'vendor/autoload.php';
require 'vendor/yiisoft/yii2/Yii.php';
$config = require 'config/web.php';
new yii\web\Application($config);

echo "Test des alias:\n";
echo "@guest.administrator_form = " . Yii::getAlias('@guest.administrator_form') . "\n";
echo "@guest.member_form = " . Yii::getAlias('@guest.member_form') . "\n";
