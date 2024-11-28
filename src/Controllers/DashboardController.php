<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Admin;

abstract class DashboardController extends BaseController
{
    protected $userModel, $adminModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->adminModel = new Admin();
    }

    protected function isLoggedIn()
    {
        return isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
    }

    protected function renderDashboardContent()
    {
        // Default dashboard content
        return '
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
    }

    public function showDashboard()
    {
        if (!$this->isLoggedIn()) {
            header('Location: /login');
            exit;
        }

        $dashboardContent = $this->renderDashboardContent();

        $data = [
            'title' => 'Dashboard',
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
