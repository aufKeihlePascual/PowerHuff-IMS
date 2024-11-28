<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Admin;

class DashboardController extends BaseController
{
    private $userModel, $adminModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        #Default Dashboard Content
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

        // Prepare data for the view
        $data = [
            'title' => 'Admin Dashboard',
            'username' => $_SESSION['username'],
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'dashboardContent' => $dashboardContent
        ];

        return $this->render('admin-dashboard', $data);
    }

    public function showUserManagement()
    {
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /login');
            exit;
        }

        $users = $this->userModel->getAllUsers();

        $data = [
            'title' => 'User Management',
            'users' => $users
        ];

        return $this->render('user-management', $data);
    }
}
