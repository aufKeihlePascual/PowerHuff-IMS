<?php

namespace App\Controllers;

class AdminController extends BaseController
{

    protected $userModel, $adminModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\User();
        $this->adminModel = new \App\Models\Admin();
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $dashboardContent = '
            <div class="welcome-box">
                <h2>Hello, ' . htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) . '!</h2>
            </div>
            <div class="overview">
                <div class="overview-card">
                    <h3>Last week overview</h3>
                    <p>$120,537.90</p>
                    <small>▼ 9.5%</small>
                    <div class="chart-line"></div>
                </div>
                <div class="overview-card">
                    <div class="chart-bar"></div>
                </div>
            </div>
            <div class="computations">
                <h3>Computations</h3>
            </div>
        ';

        $data = [
            'title' => 'Admin Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'dashboardContent' => $dashboardContent
        ];

        return $this->render('admin-dashboard', $data);
    }

    public function showUserManagement()
    {
        // if ($_SESSION['role'] !== 'Admin') {
        //     header('Location: /dashboard');
        //     exit;
        // }

        $users = $this->userModel->getAllUsers(); 

        foreach ($users as &$user) {
            $user['selectedAdmin'] = $user['role'] === 'Admin' ? 'selected' : '';
            $user['selectedInventoryManager'] = $user['role'] === 'Inventory_Manager' ? 'selected' : '';
            $user['selectedProcurementManager'] = $user['role'] === 'Procurement_Manager' ? 'selected' : '';
        }
        
        $data = [
            'title' => 'User Management',
            'users' => $users
        ];

        return $this->render('user-management', $data);
        exit;
    }

    public function showEditUserPage($user_id)
    {
        // if ($_SESSION['role'] !== 'Admin' || $_SESSION['role'] !== 'admin')  {
        //     header('Location: /dashboard');
        //     exit;
        // }

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
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $username = $_POST['username'];
                $password = $_POST['password'];
                $role = $_POST['role'];

                echo 'Role ' . $role;

                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
                $adminModel = new \App\Models\Admin();

                $userCreated = $adminModel->createUser($first_name, $last_name, $username, $passwordHash, $role);

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
