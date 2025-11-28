<?php

namespace App;

use eftec\bladeone\BladeOne;

class AuthController
{
    private $db;
    private $blade;

    public function __construct($blade)
    {
        $this->db = DB::getInstance()->getConnection();
        $this->blade = $blade;
    }

    public function showLogin()
    {
        echo $this->blade->run("auth.login");
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: /dashboard");
        } else {
            echo $this->blade->run("auth.login", ['error' => 'Invalid credentials']);
        }
    }

    public function showRegister()
    {
        echo $this->blade->run("auth.register");
    }

    public function register()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $password]);
            header("Location: /login");
        } catch (\PDOException $e) {
            if ($e->getCode() == 23000) {
                echo $this->blade->run("auth.register", ['error' => 'Email already exists']);
            } else {
                echo $this->blade->run("auth.register", ['error' => 'Registration failed. Please try again.']);
            }
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: /login");
    }
}
