<?php

namespace App\Controllers;

class InventoryManagerController extends BaseController
{
    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        // Render inventory manager dashboard view
        $data = [
            'title' => 'Inventory Manager Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
        ];

        return $this->render('inventory-manager-dashboard', $data);
    }
}
