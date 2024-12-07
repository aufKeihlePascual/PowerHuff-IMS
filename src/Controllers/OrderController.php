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
        $distinctSuppliers = $this->orderModel->getAllSuppliers();

        $statusClasses = [
            'Pending' => 'status-pending',
            'Received' => 'status-received',
            'Cancelled' => 'status-cancelled',
        ];

        foreach ($orders as &$order) {
            $order['status_class'] = $statusClasses[$order['status']] ?? 'status-default';
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Order Management',
            'username' => $_SESSION['username'],
            'orders' => $orders,
            'suppliers' => $suppliers,
            'distinctSuppliers' => $distinctSuppliers,
            
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
            if (isset($_POST['order_name'], $_POST['order_date'], $_POST['status'], $_POST['supplier_id'])) {
                $order_name = $_POST['order_name'];
                $description = isset($_POST['description']) ? $_POST['description'] : null;
                $order_date = $_POST['order_date'];
                $status = $_POST['status'];
                $supplier_id = $_POST['supplier_id'];

                $user_id = $_SESSION['user_id'];

                $data = [
                    'order_name' => $order_name,
                    'description' => $description,
                    'order_date' => $order_date,
                    'status' => $status,
                    'supplier_id' => $supplier_id,
                    'user_id' => $user_id,
                ];

                $orderCreated = $this->orderModel->createOrder($data);

                if ($orderCreated) {
                    header('Location: /dashboard/orders?order_created=true');
                    exit;
                } else {
                    echo 'There was an error in creating the order.';
                }
            } else {
                echo 'Please fill in all required fields.';
            }
        }
    }

    public function showEditOrderPage($orderId)
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            echo "Order not found.";
            return;
        }

        $order['supplier_id'] = $order['supplier_id'] ?? null;

        if ($order['order_date']) {
            $order['order_date'] = date('Y-m-d', strtotime($order['order_date']));
        }

        $order['statusPendingSelected'] = $order['status'] === 'Pending';
        $order['statusCancelledSelected'] = $order['status'] === 'Cancelled';
        $order['statusReceivedSelected'] = $order['status'] === 'Received';

        $distinctSuppliers = $this->orderModel->getAllSuppliers();

        foreach ($distinctSuppliers as &$supplier) {
            $supplier['selected'] = ($supplier['supplier_id'] == $order['supplier_id']) ? 'selected' : '';
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Edit Order',
            'username' => $_SESSION['username'],
            'order' => $order,
            'distinctSuppliers' => $distinctSuppliers,

            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('edit-order-page', $data);
    }


    public function updateOrder($orderId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['order_name'], $_POST['order_date'], $_POST['status'], $_POST['supplier_id'])) {
                $order_name = $_POST['order_name'];
                $description = isset($_POST['description']) ? $_POST['description'] : null;
                $order_date = $_POST['order_date'];
                $status = $_POST['status'];
                $supplier_id = $_POST['supplier_id'];

                $data = [
                    'order_name' => $order_name,
                    'description' => $description,
                    'order_date' => $order_date,
                    'status' => $status,
                    'supplier_id' => $supplier_id,
                ];

                $orderUpdated = $this->orderModel->updateOrder($orderId, $data);

                if ($orderUpdated) {
                    header('Location: /dashboard/orders?order_updated=true');
                    exit;
                } else {
                    echo 'There was an error updating the order.';
                }
            } else {
                echo 'Please fill in all required fields.';
            }
        }
    }

    public function deleteOrder($orderId)
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        error_log("Attempting to delete order with ID: $orderId");
        

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $orderDeleted = $this->orderModel->deleteOrder($orderId);

            if ($orderDeleted) {
                header('Location: /dashboard/orders?order_deleted=true');
                exit;
            } else {
                echo 'There was an error deleting the order.';
            }
        }
    }


}
