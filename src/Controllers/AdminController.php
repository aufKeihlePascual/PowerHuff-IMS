<?php

namespace App\Controllers;

class AdminController extends BaseController
{
    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $data = [
            'title' => 'Admin Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
        ];

        return $this->render('admin-dashboard', $data);
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password'], $_POST['role'])) {
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $role = $_POST['role'];
                // $role = ucfirst(strtolower($_POST['role']));

                echo 'Role ' . $role;

                $adminModel = new \App\Models\Admin();

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);

                $userCreated = $adminModel->createUser($first_name, $last_name, $username, $passwordHash, $role);

                if ($userCreated) {
                    header('Location: /dashboard/users');
                    exit();
                } else {
                    echo 'There was an error in creating a new user.';
                }
            } else {
                echo 'Please fill in all fields.';
            }
        }
    }

    public function deleteUser($userId)
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        error_log("Attempting to delete user with ID: $userId");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $adminModel = new \App\Models\Admin();
            
            $userDeleted = $adminModel->deleteUser($userId);

            if ($userDeleted) {
                header('Location: /dashboard/users');
                exit();
            } else {
                echo 'User does not exist or there was an error deleting the user.';
            }
        }
    }

}
