<?php
// Simple test to access administrator page directly
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Testing Administrator Page Access</h1>";
echo "<pre>";

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

try {
    $app = new yii\web\Application($config);
    
    echo "✓ Application created successfully\n";
    
    // Simulate logged in administrator
    $user = \app\models\User::findOne(1); // Assuming ID 1 exists
    if ($user) {
        echo "✓ Found user ID 1: {$user->name}\n";
        Yii::$app->user->login($user);
        echo "✓ User logged in\n";
    } else {
        echo "✗ No user with ID 1 found\n";
    }
    
    // Try to create the controller
    $controller = $app->createController('administrator/accueil');
    if ($controller) {
        echo "✓ Administrator controller created\n";
        echo "Controller class: " . get_class($controller[0]) . "\n";
    } else {
        echo "✗ Failed to create administrator controller\n";
    }
    
} catch (Exception $e) {
    echo "✗ EXCEPTION: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
