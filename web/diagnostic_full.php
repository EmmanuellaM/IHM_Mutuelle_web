<?php
// Comprehensive Diagnostic Script
header('Content-Type: text/plain');

echo "=== DIAGNOSTIC REPORT ===\n";
echo "Timestamp: " . date('Y-m-d H:i:s') . "\n\n";

// 1. Check Critical Paths
$paths = [
    'controllers/AdministratorController.php',
    'controllers/GuestController.php',
    'views/administrator/home.php',
    'views/layouts/administrator_base.php',
    'views/layouts/guest_base.php',
    'views/guest/home.php',
    'views/guest/connection.php',
    'includes/links.php',
    'includes/scripts.php',
    'web/index.php',
    'vendor/autoload.php',
    'vendor/yiisoft/yii2/Yii.php'
];

echo "=== FILE EXISTENCE CHECK ===\n";
foreach ($paths as $path) {
    $fullPath = __DIR__ . '/../' . $path;
    if (file_exists($fullPath)) {
        echo "[OK] $path (" . filesize($fullPath) . " bytes)\n";
    } else {
        echo "[MISSING] $path\n";
    }
}

// 2. Search for Debug String
echo "\n=== DEBUG STRING SEARCH ===\n";
$target = "DEBUG: GUEST";
echo "Searching for '$target' in all PHP files...\n";

function searchRecursively($dir, $target) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && $file->getExtension() === 'php') {
            $content = file_get_contents($file->getPathname());
            if (strpos($content, $target) !== false) {
                echo "FOUND in: " . $file->getPathname() . "\n";
            }
        }
    }
}

searchRecursively(__DIR__ . '/../', $target);

// 3. Test View Rendering (Validation)
echo "\n=== VIEW RENDERING SIMULATION ===\n";
try {
    // Simple render test
    ob_start();
    include __DIR__ . '/../includes/links.php'; // Should fail if Yii not loaded, checking output
    $output = ob_get_clean();
    echo "Includes/links.php check: " . (strlen($output) > 0 ? "Content Generated" : "Empty") . "\n";
} catch (Exception $e) {
    echo "Error including links: " . $e->getMessage() . "\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
