<?php
/**
 * Router script for PHP built-in server
 * Routes all requests through index.php except static files
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Serve static files directly
if ($uri !== '/' && file_exists($file) && is_file($file)) {
    return false; // Let PHP serve the file
}

// All other requests go through index.php
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/index.php';
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

require __DIR__ . '/index.php';
