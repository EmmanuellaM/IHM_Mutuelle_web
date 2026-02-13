<?php
// Diagnostic script to check CSS file availability
header('Content-Type: text/plain');

echo "=== CSS Files Diagnostic ===\n\n";

$cssFiles = [
    'css/bootstrap/bootstrap.min.css',
    'css/all.min.css',
    'css/mdb.min.css',
    'css/loader.css',
    'css/main.css',
    'css/print.css',
    'css/member.css',
    'css/guest.css',
];

foreach ($cssFiles as $file) {
    $fullPath = __DIR__ . '/' . $file;
    $exists = file_exists($fullPath);
    $readable = is_readable($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    
    echo "$file:\n";
    echo "  Exists: " . ($exists ? 'YES' : 'NO') . "\n";
    echo "  Readable: " . ($readable ? 'YES' : 'NO') . "\n";
    echo "  Size: " . $size . " bytes\n";
    echo "  Full path: $fullPath\n\n";
}

echo "\n=== Web Root ===\n";
echo "Current directory: " . __DIR__ . "\n";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
