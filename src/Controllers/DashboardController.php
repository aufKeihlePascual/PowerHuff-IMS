<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $dashboardContent = $this->renderWelcomeDashboard();

        $data = [
            'title' => 'Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'dashboardContentJS' => json_encode($dashboardContent),
        ];

        return $this->render('dashboard', $data);
    }

    public function loadContent()
    {
        $content = $_GET['content'] ?? 'dashboard';

        $contentPages = $this->getContentPages();

        // Renders content page, else welcome page default
        echo $contentPages[$content] ?? $contentPages['dashboard'];
    }

    private function renderWelcomeDashboard()
    {
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

    // Consolidated function to get all content pages
    private function getContentPages()
    {
        return [
            'dashboard' => $this->renderWelcomeDashboard(),
            'products' => '<h1>Products</h1><p>Manage your products here.</p>',
            'categories' => '<h1>Categories</h1><p>Manage your categories here.</p>',
            'reports' => '<h1>Reports</h1><p>View your reports here.</p>',
            'order' => '<h1>Order</h1><p>This feature is under development.</p>',
            'suppliers' => '<h1>Suppliers</h1><p>This feature is under development.</p>',
            'users' => '<h1>Users</h1><p>This feature is under development.</p>',
            'stocks' => '<h1>Stocks</h1><p>This feature is under development.</p>',
        ];
    }
}