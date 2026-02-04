<?php
// Test rapide de l'alias
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$app = new yii\web\Application($config);

header('Content-Type: text/plain');
echo "Alias @guest.administrator_form = " . Yii::getAlias('@guest.administrator_form');
