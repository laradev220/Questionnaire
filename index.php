<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include config and functions
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/admin.php';
require_once 'includes/auth.php';
require_once 'includes/survey.php';

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace(BASE_PATH, '', $uri);
if (empty($uri)) {
    $uri = '/';
}
$method = $_SERVER['REQUEST_METHOD'];

// Middleware check
$isAdminLoggedIn = isset($_SESSION['admin_id']);

if ($uri === '/') {
    if ($method === 'POST') {
        // Handle participant form submission
        survey_start();
    } else {
        // Show participant form
        include 'templates/survey/participant_form.php';
    }
} elseif ($uri === '/progress') {
    survey_dashboard();
} elseif ($uri === '/survey') {
    $module = $_GET['module'] ?? 1;
    if ($method === 'POST') {
        $page = $_POST['page'] ?? 1;
        survey_store_module($module, $page);
    } else {
        $page = $_GET['page'] ?? 1;
        survey_show_module($module, $page);
    }
} elseif ($uri === '/thank-you') {
    include 'templates/survey/thank-you.php';
} elseif ($uri === '/login') {
    if ($method === 'POST') auth_login();
    else auth_show_login();
} elseif ($uri === '/register') {
    if ($method === 'POST') auth_register();
    else auth_show_register();
} elseif ($uri === '/logout') {
    auth_logout();
} elseif ($uri === '/admin/login') {
    if ($method === 'POST') admin_login();
    else admin_show_login();
} elseif ($uri === '/admin/logout') {
    admin_logout();
} elseif ($isAdminLoggedIn) {
    if ($uri === '/admin/dashboard') {
        admin_dashboard();
    } elseif (preg_match('#^/admin/questions$#', $uri)) {
        admin_questions();
    } elseif (preg_match('#^/admin/questions/add$#', $uri)) {
        admin_add_question();
    } elseif (preg_match('#^/admin/questions/edit/(\d+)$#', $uri, $matches)) {
        admin_edit_question($matches[1]);
    } elseif (preg_match('#^/admin/questions/delete/(\d+)$#', $uri, $matches)) {
        admin_delete_question($matches[1]);
    } elseif ($uri === '/admin/analytics') {
        admin_analytics();
    } else {
        echo "404 Not Found";
    }
} else {
    // For admin routes, redirect to admin/login if not logged in
    if (strpos($uri, '/admin/') === 0) {
        header("Location: /admin/login");
        exit;
    }
    echo "404 Not Found";
}
