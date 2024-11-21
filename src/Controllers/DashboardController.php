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

        $data = [
            'title' => 'Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name']
        ];

        return $this->render('dashboard', $data);
    }

    public function loadContent()
    {
        $content = $_GET['content'] ?? 'dashboard'; 

        $contentPages = [
            'dashboard' => $this->renderContent('dashboard'),
            'products' => $this->renderContent('products'),
            'categories' => $this->renderContent('categories'),
            'reports' => $this->renderContent('reports'),
        ];

        echo $contentPages[$content] ?? $contentPages['dashboard'];
    }

    private function renderContent($page)
    {
        $content = [
            'dashboard' => '<h1>Dashboard</h1><p>Welcome to your inventory dashboard!</p>',
            'products' => '<h1>Products</h1><p>Manage your products here.</p>',
            'categories' => '<h1>Categories</h1><p>Manage your categories here.</p>',
            'reports' => '<h1>Reports</h1><p>View your reports here.</p>',
        ];

        return $content[$page] ?? $content['dashboard'];
    }
}