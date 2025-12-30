<?php
// Authentication and Authorization Functions for Multi-Tenant System

/**
 * Get current authenticated user data
 */
function get_authenticated_user() {
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    static $user = null;
    if ($user === null) {
        $db = get_db_connection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
    return $user;
}

/**
 * Check if current user is admin
 */
function is_admin() {
    $user = get_authenticated_user();
    return $user && $user['role'] === 'admin';
}

/**
 * Check if current user is researcher
 */
function is_researcher() {
    $user = get_authenticated_user();
    return $user && ($user['role'] === 'researcher' || $user['role'] === 'admin');
}

/**
 * Require authentication - redirect to login if not authenticated
 */
function require_auth() {
    if (!get_current_user()) {
        header("Location: " . BASE_PATH . "/login");
        exit;
    }
}

/**
 * Require admin access - redirect if not admin
 */
function require_admin() {
    if (!is_admin()) {
        header("Location: " . BASE_PATH . "/login");
        exit;
    }
}

/**
 * Require researcher access (researcher or admin)
 */
function require_researcher() {
    if (!is_researcher()) {
        header("Location: " . BASE_PATH . "/login");
        exit;
    }
}

/**
 * Check if current user can access a specific survey
 */
function can_access_survey($survey_id) {
    $user = get_authenticated_user();
    if (!$user) {
        return false;
    }

    // Admin can access any survey
    if (is_admin()) {
        return true;
    }

    // Researchers can only access their own surveys
    $db = get_db_connection();
    $stmt = $db->prepare("SELECT user_id FROM surveys WHERE id = ?");
    $stmt->execute([$survey_id]);
    $survey = $stmt->fetch();

    return $survey && $survey['user_id'] == $user['id'];
}

/**
 * Get user role display name
 */
function get_user_role_display($role) {
    $roles = [
        'researcher' => 'Researcher',
        'admin' => 'Administrator'
    ];
    return $roles[$role] ?? 'Unknown';
}

function auth_show_login() {
    // If already logged in, redirect to appropriate dashboard
    if (get_authenticated_user()) {
        if (is_admin()) {
            header("Location: " . BASE_PATH . "/admin/dashboard");
        } else {
            header("Location: " . BASE_PATH . "/dashboard");
        }
        exit;
    }

    include __DIR__ . '/../templates/auth/login.php';
}

function auth_login() {
    $db = get_db_connection();
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: " . BASE_PATH . "/admin/dashboard");
        } else {
            header("Location: " . BASE_PATH . "/dashboard");
        }
        exit;
    } else {
        $error = 'Invalid email or password';
        include __DIR__ . '/../templates/auth/login.php';
    }
}

function auth_show_register() {
    // Don't allow registration if already logged in
    if (get_authenticated_user()) {
        header("Location: " . BASE_PATH . "/dashboard");
        exit;
    }

    include __DIR__ . '/../templates/auth/register.php';
}

function auth_register() {
    $db = get_db_connection();
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Basic validation
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'All fields are required';
        include __DIR__ . '/../templates/auth/register.php';
        return;
    }

    if (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters long';
        include __DIR__ . '/../templates/auth/register.php';
        return;
    }

    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'researcher')");
        $stmt->execute([$name, $email, $hashed_password]);

        // Auto-login after registration
        $user_id = $db->lastInsertId();
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $name;
        $_SESSION['user_role'] = 'researcher';

        header("Location: " . BASE_PATH . "/dashboard");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $error = 'Email already exists';
        } else {
            $error = 'Registration failed. Please try again.';
        }
        include __DIR__ . '/../templates/auth/register.php';
    }
}

function auth_logout() {
    session_destroy();
    header("Location: " . BASE_PATH . "/login");
    exit;
}
?>