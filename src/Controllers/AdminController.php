<?php

namespace App\Controllers;

class AdminController extends DashboardController
{
    protected $userModel, $adminModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\User();
        $this->adminModel = new \App\Models\Admin();
    }

    public function showUserManagement()
    {
        $users = $this->userModel->getAllUsers(); 

        foreach ($users as &$user) {
            $user['selectedAdmin'] = $user['role'] === 'Admin' ? 'selected' : '';
            $user['selectedInventoryManager'] = $user['role'] === 'Inventory_Manager' ? 'selected' : '';
            $user['selectedProcurementManager'] = $user['role'] === 'Procurement_Manager' ? 'selected' : '';
        }
        
        $data = [
            'title' => 'User Management',
            'users' => $users,
            'user_name' => $_SESSION['username'],
        ];

        return $this->render('user-management', $data);
        exit;
    }

    public function showEditUserPage($user_id)
    {
        $user = $this->userModel->findByUserID($user_id);

        if (!$user) {
            echo 'User not found.';
            exit;
        }

        $data = [
            'title' => 'Edit User',
            'user_id' => $user['user_id'],
            'first_name' => $user['first_name'],
            'last_name' => $user['last_name'],
            'username' => $user['username'],
            'selectedAdmin' => $user['role'] === 'Admin' ? 'selected' : '',
            'selectedInventoryManager' => $user['role'] === 'Inventory_Manager' ? 'selected' : '',
            'selectedProcurementManager' => $user['role'] === 'Procurement_Manager' ? 'selected' : '',
        ];

        return $this->render('edit-user-page', $data);
    }

    public function createUser()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password'], $_POST['role'])) {
                $first_name = trim($_POST['first_name']);
                $last_name = trim($_POST['last_name']);
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');
                $role = $_POST['role'];

                echo 'Role ' . $role;

                $data = [
                    'first_name' => $first_name,
                    'last_name' => $last_name,
                    'username' => $username,
                    'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                    'role' => $role,
                    'created_on' => date('Y-m-d H:i:s'),
                ];
                
                $userCreated = $this->adminModel->createUser($data);

                if ($userCreated) {
                    header('Location: /dashboard/users?user_created=true');
                    exit();
                } else {
                    echo 'There was an error in creating a new user.';
                }
            } else {
                echo 'Please fill in all fields.';
            }
        }
    }

    public function updateUser($user_id)
    {
        if ($_SESSION['role'] !== 'Admin') {
            header('Location: /dashboard');
            exit;
        }

        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $username = $_POST['username'];
        $password = $_POST['password_hash'];
        $role = $_POST['role'];


        $data = [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'role' => $role,
            'updated_on' => date('Y-m-d H:i:s'),
        ];

        if (!empty($password)) {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $result = $this->adminModel->updateUser($user_id, $data);

        if ($result) {
            header('Location: /dashboard/users');
            exit;
        } else {
            echo 'Failed to update user.';
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
