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
        $users = $this->userModel->getAllUsers(); 

        $data = [
            'title' => 'User Management',
            'users' => $users
        ];

        return $this->render('user-management', $data);
        exit;
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

    public function editUser()
    {
        global $conn;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['user_id'], $_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['role'])) {
                // Get the POST data
                $user_id = (int) $_POST['user_id'];  // Cast user_id to integer
                $first_name = $_POST['first_name'];
                $last_name = $_POST['last_name'];
                $username = $_POST['username'];
                $role = $_POST['role'];

                // If a new password is provided, hash it
                $password = isset($_POST['password']) && !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

                // Call the model method to update the user
                $adminModel = new \App\Models\Admin();
                $updateSuccess = $adminModel->EditUser($user_id, $first_name, $last_name, $username, $role, $password);

                if ($updateSuccess) {
                    // Redirect to users list page
                    header('Location: /dashboard/users?user_updated=true');
                    exit();
                } else {
                    echo 'Error updating user.';
                }
            } else {
                echo 'Missing fields.';
            }

            $user_id = $_POST['user_id'];

            error_log("User ID being updated: " . $user_id);  // Log the user_id

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
