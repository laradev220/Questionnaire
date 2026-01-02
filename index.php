<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include config and functions
require_once 'config.php';
require_once 'includes/db.php';
require_once 'includes/admin.php';
require_once 'includes/auth.php';
require_once 'includes/survey.php';
require_once 'includes/security.php';

// Simple Router
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace(BASE_PATH, '', $uri);
if (empty($uri)) {
    $uri = '/';
}
$method = $_SERVER['REQUEST_METHOD'];

// Include required files
require_once 'includes/db.php';
require_once 'includes/auth.php';
require_once 'includes/admin.php';
require_once 'includes/survey.php';
require_once 'includes/researcher.php';

// Public routes (no authentication required)
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

    // Authentication routes
} elseif ($uri === '/login') {
    if ($method === 'POST') auth_login();
    else auth_show_login();
} elseif ($uri === '/register') {
    if ($method === 'POST') auth_register();
    else auth_show_register();
} elseif ($uri === '/logout') {
    auth_logout();

    // Researcher routes (require researcher or admin access)
} elseif (preg_match('#^/dashboard$#', $uri)) {
    require_researcher();
    researcher_dashboard();
} elseif (preg_match('#^/surveys/create$#', $uri)) {
    require_researcher();
    researcher_create_survey();
} elseif (preg_match('#^/surveys/(\d+)/edit$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    researcher_edit_survey($survey_id);
} elseif (preg_match('#^/surveys/(\d+)/questions$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    researcher_assign_questions($survey_id);
} elseif (preg_match('#^/surveys/(\d+)/link$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    researcher_generate_link($survey_id);
} elseif (preg_match('#^/surveys/(\d+)/delete$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['confirm'])) {
        researcher_confirm_delete_survey($survey_id);
    } else {
        researcher_delete_survey($survey_id);
    }
} elseif (preg_match('#^/surveys/(\d+)/questions$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        researcher_update_survey_questions($survey_id);
    } else {
        researcher_assign_questions($survey_id);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        researcher_confirm_delete_survey($survey_id);
    } else {
        researcher_delete_survey($survey_id);
    }
} elseif (preg_match('#^/surveys/(\d+)/analytics$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    researcher_survey_analytics($survey_id);
} elseif (preg_match('#^/surveys/(\d+)/participants$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }
    researcher_survey_participants($survey_id);
} elseif (preg_match('#^/surveys/(\d+)/participants/(\d+)$#', $uri, $matches)) {
    require_researcher();
    $survey_id = $matches[1];
    $participant_id = $matches[2];
    if (!can_access_survey($survey_id)) {
        http_response_code(403);
        echo "Access denied";
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        researcher_update_participant_responses($survey_id, $participant_id);
    } else {
        researcher_participant_responses($survey_id, $participant_id);
    }
} elseif (preg_match('#^/profile$#', $uri)) {
    require_researcher();
    include 'templates/researcher/profile.php';
} elseif (preg_match('#^/questions$#', $uri)) {
    require_researcher();
    researcher_questions();
} elseif (preg_match('#^/questions/create$#', $uri)) {
    require_researcher();
    researcher_create_question();
} elseif (preg_match('#^/questions/(\d+)/edit$#', $uri, $matches)) {
    require_researcher();
    $question_id = $matches[1];
    researcher_edit_question($question_id);
} elseif (preg_match('#^/questions/(\d+)/delete$#', $uri, $matches)) {
    require_researcher();
    $question_id = $matches[1];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        researcher_delete_question($question_id);
    } else {
        // Show confirmation or just delete
        researcher_delete_question($question_id);
    }

    // Survey access by token (public but controlled)
} elseif (preg_match('#^/s/([a-zA-Z0-9]+)$#', $uri, $matches)) {
    $token = $matches[1];
    survey_start_by_token($token);

    // Admin routes (require admin access)
} elseif ($uri === '/admin/login') {
    if ($method === 'POST') {
        admin_login();
    } else {
        admin_show_login();
    }
} elseif ($uri === '/admin/logout') {
    auth_logout(); // Use the same logout function

} elseif (preg_match('#^/admin/dashboard$#', $uri)) {
    require_admin();
    admin_dashboard();
} elseif (preg_match('#^/admin/questions$#', $uri)) {
    require_admin();
    admin_questions();
} elseif (preg_match('#^/admin/questions/add$#', $uri)) {
    require_admin();
    if ($method === 'POST') admin_add_question();
    else include 'templates/admin/add_question.php';
} elseif (preg_match('#^/admin/questions/edit/(\d+)$#', $uri, $matches)) {
    require_admin();
    if ($method === 'POST') admin_edit_question($matches[1]);
    else admin_edit_question($matches[1]);
} elseif (preg_match('#^/admin/questions/delete/(\d+)$#', $uri, $matches)) {
    require_admin();
    admin_delete_question($matches[1]);
} elseif (preg_match('#^/admin/questions/bulk$#', $uri)) {
    require_admin();
    if ($method === 'POST') admin_bulk_questions();
    else admin_bulk_questions();
} elseif (preg_match('#^/admin/analytics$#', $uri)) {
    require_admin();
    admin_analytics();
} elseif (preg_match('#^/admin/export/participants$#', $uri)) {
    require_admin();
    admin_export_participants();
} elseif (preg_match('#^/admin/export/responses$#', $uri)) {
    require_admin();
    admin_export_responses();

    // New super admin routes
} elseif (preg_match('#^/admin/users$#', $uri)) {
    require_super_admin();
    admin_users();
} elseif (preg_match('#^/admin/users/add$#', $uri)) {
    require_super_admin();
    if ($method === 'POST') admin_add_user();
    else admin_add_user();
} elseif (preg_match('#^/admin/users/(\d+)/edit$#', $uri, $matches)) {
    require_super_admin();
    if ($method === 'POST') admin_edit_user($matches[1]);
    else admin_edit_user($matches[1]);
} elseif (preg_match('#^/admin/users/(\d+)/delete$#', $uri, $matches)) {
    require_super_admin();
    admin_delete_user($matches[1]);
} elseif (preg_match('#^/admin/users/bulk$#', $uri)) {
    require_super_admin();
    if ($method === 'POST') admin_bulk_users();
    else admin_bulk_users();
} elseif (preg_match('#^/admin/surveys$#', $uri)) {
    require_admin();
    admin_surveys();
} elseif (preg_match('#^/admin/surveys/add$#', $uri)) {
    require_admin();
    if ($method === 'POST') admin_add_survey();
    else admin_add_survey();
} elseif (preg_match('#^/admin/surveys/(\d+)/edit$#', $uri, $matches)) {
    require_admin();
    if ($method === 'POST') admin_edit_survey($matches[1]);
    else admin_edit_survey($matches[1]);
} elseif (preg_match('#^/admin/surveys/(\d+)/delete$#', $uri, $matches)) {
    require_admin();
    admin_delete_survey($matches[1]);
} elseif (preg_match('#^/admin/surveys/bulk$#', $uri)) {
    require_admin();
    if ($method === 'POST') admin_bulk_surveys();
    else admin_bulk_surveys();
} elseif (preg_match('#^/admin/participants$#', $uri)) {
    require_admin();
    admin_participants();
} elseif (preg_match('#^/admin/participants/(\d+)/responses$#', $uri, $matches)) {
    require_admin();
    admin_view_participant_responses($matches[1]);
} elseif (preg_match('#^/admin/participants/(\d+)/delete$#', $uri, $matches)) {
    require_admin();
    admin_delete_participant($matches[1]);
} elseif (preg_match('#^/admin/participants/bulk$#', $uri)) {
    require_admin();
    if ($method === 'POST') admin_bulk_participants();
    else admin_bulk_participants();
} elseif (preg_match('#^/admin/responses$#', $uri)) {
    require_admin();
    admin_responses();
} elseif (preg_match('#^/admin/responses/(\d+)/edit$#', $uri, $matches)) {
    require_admin();
    if ($method === 'POST') admin_edit_response($matches[1]);
    else admin_edit_response($matches[1]);
} elseif (preg_match('#^/admin/responses/(\d+)/delete$#', $uri, $matches)) {
    require_admin();
    admin_delete_response($matches[1]);

    // Catch-all for unrecognized routes
} else {
    // Check if it's an admin route that requires login
    if (strpos($uri, '/admin/') === 0) {
        header("Location: " . BASE_PATH . "/admin/login");
        exit;
    }

    // Check if it's a researcher route that requires login
    if (preg_match('#^/(dashboard|surveys/)#', $uri)) {
        header("Location: " . BASE_PATH . "/login");
        exit;
    }

    echo "404 Not Found";
}
