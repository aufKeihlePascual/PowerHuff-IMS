<?php

namespace App\Controllers;

class ActivityController extends BaseController
{
    protected $activityModel, $userModel;

    public function __construct()
    {
        $this->activityModel = new \App\Models\Activity();
        $this->userModel = new \App\Models\User();
    }

    private function renderActivityReport($title, $view)
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $role = $_SESSION['role'];
        $filter = $_GET;

        $activities = $this->activityModel->getUserActivities($filter);
        $users = $this->userModel->getAllUsers();

        $data = [
            'title' => $title,
            'username' => $_SESSION['username'],
            'activities' => $activities,
            'users' => $users,
            'filter' => $filter,
            'pdfLinks' => [
                'productActivity' => '/generate-pdf/product_activity',
                'stockActivity' => '/generate-pdf/stock_activity',
            ],

            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            echo $this->render('partials/activity-table', $data);
            exit;
        }

        return $this->render($view, $data);
    }

    public function showProductActivity()
    {
        return $this->renderActivityReport('Product Report', 'product-activity');
    }

    public function showStockActivity()
    {
        return $this->renderActivityReport('Stock Report', 'stock-activity');
    }
}
