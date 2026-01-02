<?php
// Load environment variables from .env file
function loadEnv($path)
{
    if (!file_exists($path)) {
        throw new Exception('.env file not found');
    }
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        putenv("$name=$value");
    }
}

loadEnv(__DIR__ . '/.env');

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'research_app');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// Other config
define('TIMEZONE', getenv('TIMEZONE') ?: 'Asia/Karachi');
date_default_timezone_set(TIMEZONE);

// Detect base path for subfolder installations
$scriptName = $_SERVER['SCRIPT_NAME'];
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}
define('BASE_PATH', $basePath);

// Session start
session_start();
