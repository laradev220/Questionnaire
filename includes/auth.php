<?php
function auth_show_login() {
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
        header("Location: " . BASE_PATH . "/dashboard");
    } else {
        $error = 'Invalid credentials';
        include __DIR__ . '/../templates/auth/login.php';
    }
}

function auth_show_register() {
    include __DIR__ . '/../templates/auth/register.php';
}

function auth_register() {
    $db = get_db_connection();
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        header("Location: " . BASE_PATH . "/login");
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
}
?>