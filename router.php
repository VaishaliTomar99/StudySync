<?php
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve static files directly
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Route to index.php by default
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';