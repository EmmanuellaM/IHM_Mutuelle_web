<?php
require 'vendor/autoload.php'; 
require 'vendor/yiisoft/yii2/Yii.php'; 
$config = require 'config/web.php'; 
new yii\web\Application($config); 

echo "Dropping table 'renflouement'...\n";
try {
    Yii::$app->db->createCommand('DROP TABLE IF EXISTS renflouement')->execute();
    echo "Table dropped successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
