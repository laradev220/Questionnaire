<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'research_app');
define('DB_USER', 'root');
define('DB_PASS', 'Irfan@234$');

// Other config
define('TIMEZONE', 'Asia/Karachi');
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
