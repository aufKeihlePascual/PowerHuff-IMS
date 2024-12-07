<?php

namespace App\Controllers;

class OrderController extends BaseController
{
    protected $orderModel, $supplierModel;

    public function __construct()
    {
        $this->orderModel = new \App\Models\Order();
        $this->supplierModel = new \App\Models\Supplier();
    }

    public function showOrdersPage()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $orders = $this->orderModel->getAllOrders();
        $suppliers = $this->supplierModel->getAllSuppliers();

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Orders',
            'user_name' => $_SESSION['username'],
            'orders' => $orders,
            'suppliers' => $suppliers,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('order-management', $data);
    }

    public function createOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['order_name'], $_POST['description'], $_POST['order_date'], $_POST['status'], $_POST['supplier_id'])) {
                $order_name = $_POST['order_name'];
                $description = isset($_POST['description']) ? $_POST['description'] : null;
                $order_date = $_POST['order_date'];
                $status = $_POST['status'];
                $supplier_id = $_POST['supplier_id'];
                $user_id = $_POST['supplier_id'];

                $data = [
                    'order_name' => $order_date,
                    'description' => $description,
                    'order_name' => $order_date,
                    'status' => $status,
                    'supplier_id' => $supplier_id,
                    'user_id' => $user_id,
                ];

                $orderCreated = $this->orderModel->createOrder($order_name, $description, $order_date, $status, $supplier_id, $user_id);

                if ($orderCreated) {
                    header('Location: /orders?order_created=true');
                    exit;
                } else {
                    echo 'There was an error in creating a new user.';
                }
            } else {
                echo 'Please fill in all fields.';
            }
        }
    }

}
