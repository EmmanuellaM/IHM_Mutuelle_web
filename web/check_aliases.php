<?php
// Test Yii aliases
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';
$app = new yii\web\Application($config);

header('Content-Type: text/plain');

echo "=== Yii Aliases Test ===\n\n";
echo "@web = " . Yii::getAlias('@web') . "\n";
echo "@webroot = " . Yii::getAlias('@webroot') . "\n";
echo "@app = " . Yii::getAlias('@app') . "\n";
echo "\n";

echo "=== URL Test ===\n";
echo "Current URL: " . Yii::$app->request->getHostInfo() . "\n";
echo "Base URL: " . Yii::$app->request->getBaseUrl() . "\n";
echo "Script URL: " . Yii::$app->request->getScriptUrl() . "\n";
echo "\n";

echo "=== Full CSS URL ===\n";
$cssUrl = Yii::getAlias("@web") . "/css/bootstrap/bootstrap.min.css";
echo "Generated: $cssUrl\n";
echo "Should be: /css/bootstrap/bootstrap.min.css or https://domain.com/css/bootstrap/bootstrap.min.css\n";
