<?php

namespace App\Controllers;

use App\Models\User;

class LoginController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function showLoginForm()
    {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
            header('Location: /dashboard');
            exit;
        }

        $data = [
            'title' => 'Login'
        ];
        return $this->render('login', $data);
    }

    public function login()
    {
        print_r($_SESSION);

        if (!isset($_SESSION['login_attempts'])) {
            $_SESSION['login_attempts'] = 0;
        }

        if ($_SESSION['login_attempts'] >= 3) {
            $data = [
                'title' => 'Login',
                'error' => 'Too many failed attempts. Please try again later.'
            ];
            return $this->render('login', $data);
        }

        $username = trim($_POST['username'] ?? '');
        $password = trim($_POST['password'] ?? '');

        $user = $this->userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password_hash'])) {
        // if ($user && $user['password_hash'] === $password) { //testing for plain text passwords
            $_SESSION['login_attempts'] = 0;

            $_SESSION['is_logged_in'] = true;
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'Admin') {
                header('Location: /admin-dashboard');
                exit;
            } elseif ($user['role'] === 'Inventory_Manager') {
                header('Location: /inventory-manager-dashboard');
                exit;
            }
        } else {
            $_SESSION['login_attempts'] += 1;

            $data = [
                'title' => 'Login',
                'error' => 'Login failed. Please check your username and password.'
            ];
            return $this->render('login', $data);
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /login');
        exit;
    }
}
