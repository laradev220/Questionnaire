<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use eftec\bladeone\BladeOne;
use App\AuthController;
use App\SurveyController;
use App\AdminController;

// Blade Configuration
$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';
$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Middleware check
$isAdminLoggedIn = isset($_SESSION['admin_id']);

if ($uri === '/') {
    if ($method === 'POST') {
        // Handle participant form submission
        $survey = new SurveyController($blade);
        $survey->startSurvey();
    } else {
        // Show participant form
        echo $blade->run("survey.participant_form");
    }
} elseif ($uri === '/dashboard') {
    $survey = new SurveyController($blade);
    $survey->dashboard();
} elseif ($uri === '/survey') {
    $survey = new SurveyController($blade);
    $module = $_GET['module'] ?? 1;
    if ($method === 'POST') {
        $page = $_POST['page'] ?? 1;
        $survey->storeModule($module, $page);
    } else {
        $page = $_GET['page'] ?? 1;
        $survey->showModule($module, $page);
    }
} elseif ($uri === '/thank-you') {
    echo $blade->run("survey.thank-you");
} elseif ($uri === '/admin/login') {
    $admin = new AdminController($blade);
    if ($method === 'POST') $admin->login();
    else $admin->showLogin();
} elseif ($uri === '/admin/logout') {
    (new AdminController($blade))->logout();
} elseif ($isAdminLoggedIn) {
    if ($uri === '/admin/dashboard') {
        $admin = new AdminController($blade);
        $admin->dashboard();
    } elseif ($uri === '/dashboard') {
        // For admin, perhaps redirect or something, but since admin can access survey? No, admin is separate.
        header("Location: /admin/dashboard");
        exit;
    } elseif (preg_match('#^/admin/questions$#', $uri)) {
        $admin = new AdminController($blade);
        $admin->questions();
    } elseif (preg_match('#^/admin/questions/add$#', $uri)) {
        $admin = new AdminController($blade);
        $admin->addQuestion();
    } elseif (preg_match('#^/admin/questions/edit/(\d+)$#', $uri, $matches)) {
        $admin = new AdminController($blade);
        $admin->editQuestion($matches[1]);
    } elseif (preg_match('#^/admin/questions/delete/(\d+)$#', $uri, $matches)) {
        $admin = new AdminController($blade);
        $admin->deleteQuestion($matches[1]);
    } elseif ($uri === '/admin/analytics') {
        $admin = new AdminController($blade);
        $admin->analytics();
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
