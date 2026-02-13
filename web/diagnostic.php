<?php
// Comprehensive diagnostic for blank pages
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!DOCTYPE html><html><head><title>Diagnostic</title></head><body>";
echo "<h1>Blank Page Diagnostic</h1>";
echo "<pre>";

echo "=== PHP Info ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Display Errors: " . ini_get('display_errors') . "\n";
echo "Error Reporting: " . error_reporting() . "\n\n";

echo "=== File Check ===\n";
echo "Current file: " . __FILE__ . "\n";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "Script filename: " . $_SERVER['SCRIPT_FILENAME'] . "\n\n";

echo "=== Yii Bootstrap ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "✓ Autoload successful\n";
    
    require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';
    echo "✓ Yii loaded\n";
    
    $config = require __DIR__ . '/../config/web.php';
    echo "✓ Config loaded\n";
    
    $app = new yii\web\Application($config);
    echo "✓ Application created\n";
    
    echo "\n=== Session Test ===\n";
    echo "Session status: " . session_status() . "\n";
    
    // Try to start session manually
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
        echo "✓ Session started manually\n";
    } else {
        echo "Session already active\n";
    }
    
    echo "\n=== User Authentication Test ===\n";
    if (Yii::$app->user->isGuest) {
        echo "User is GUEST (not logged in)\n";
    } else {
        echo "User is logged in, ID: " . Yii::$app->user->id . "\n";
    }
    
    echo "\n=== Controller Test ===\n";
    // Try to access administrator controller
    try {
        $result = $app->createController('administrator/accueil');
        if ($result) {
            echo "✓ Administrator controller can be created\n";
            echo "Controller: " . get_class($result[0]) . "\n";
        } else {
            echo "✗ Failed to create administrator controller\n";
        }
    } catch (Exception $e) {
        echo "✗ Exception creating controller: " . $e->getMessage() . "\n";
    }
    
    echo "\n=== Database Test ===\n";
    try {
        $db = Yii::$app->db;
        echo "✓ Database connection exists\n";
        
        $users = \app\models\User::find()->limit(1)->all();
        echo "✓ Can query User table, found " . count($users) . " user(s)\n";
    } catch (Exception $e) {
        echo "✗ Database error: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "✗ FATAL ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Headers Check ===\n";
if (!headers_sent()) {
    echo "✓ Headers not sent yet (good)\n";
} else {
    echo "✗ Headers already sent\n";
}

echo "</pre></body></html>";
