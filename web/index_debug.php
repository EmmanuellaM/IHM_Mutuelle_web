<?php
// Enhanced error logging for debugging blank pages
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../runtime/debug.log');
error_reporting(E_ALL);

// Custom error handler to catch everything
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $message = "ERROR [$errno]: $errstr in $errfile on line $errline\n";
    file_put_contents(__DIR__ . '/../runtime/debug.log', $message, FILE_APPEND);
    echo "<pre style='background:red;color:white;padding:20px;'>$message</pre>";
    return false;
});

// Custom exception handler
set_exception_handler(function($exception) {
    $message = "EXCEPTION: " . $exception->getMessage() . "\n";
    $message .= "File: " . $exception->getFile() . "\n";
    $message .= "Line: " . $exception->getLine() . "\n";
    $message .= "Trace:\n" . $exception->getTraceAsString() . "\n";
    file_put_contents(__DIR__ . '/../runtime/debug.log', $message, FILE_APPEND);
    echo "<pre style='background:red;color:white;padding:20px;'>$message</pre>";
});

// Register shutdown function to catch fatal errors
register_shutdown_function(function() {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $message = "FATAL ERROR: {$error['message']} in {$error['file']} on line {$error['line']}\n";
        file_put_contents(__DIR__ . '/../runtime/debug.log', $message, FILE_APPEND);
        echo "<pre style='background:red;color:white;padding:20px;'>$message</pre>";
    }
});

echo "Error logging initialized. Check runtime/debug.log for errors.\n";

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

try {
    (new yii\web\Application($config))->run();
} catch (Exception $e) {
    echo "<pre style='background:red;color:white;padding:20px;'>";
    echo "APPLICATION EXCEPTION:\n";
    echo $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "</pre>";
}
