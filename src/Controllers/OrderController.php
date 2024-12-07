<?php

namespace App\Controllers;

class OrderController extends BaseController
{
    protected $orderModel;

    public function __construct()
    {
        $this->orderModel = new \App\Models\Order();
    }

    public function showOrdersPage()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $orders = $this->orderModel->getAllOrders();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Orders',
            'user_name' => $_SESSION['username'],
            'orders' => $orders,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('order-management', $data);
    }
        
}
