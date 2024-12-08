<?php

namespace App\Controllers;

require 'vendor/autoload.php';

use \Fpdf\Fpdf;

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
                $status = 'Pending';
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

    public function showOrderDetails($orderId)
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

        $orderItems = $this->orderModel->getOrderItems($orderId);

        $orderTotal = 0;
        foreach ($orderItems as &$item) {
            $item['total'] = $item['quantity'] * $item['price'];
            $orderTotal += $item['total'];
        }

        $orderTotalFormatted = number_format($orderTotal, 2);
        $data['orderTotal'] = $orderTotalFormatted;

        $statusClasses = [
            'Pending' => 'status-pending',
            'Cancelled' => 'status-cancelled',
            'Received' => 'status-received'
        ];

        $statusClass = isset($statusClasses[$order['status']]) ? $statusClasses[$order['status']] : 'status-default';

        $order['statusClass'] = $statusClass;

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Order Details',
            'username' => $_SESSION['username'],
            'order' => $order,
            'orderItems' => $orderItems,
            'orderTotal' => $data['orderTotal'],
            'statusClass' => $statusClass,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('order-details', $data);
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

    public function generatePdf()
    {
        if (!isset($_POST['order_id'])) {
            echo "Order ID is required!";
            exit;
        }

        $orderId = $_POST['order_id'];

        $order = $this->orderModel->getOrderById($orderId);
        
        if (!$order) {
            echo "Order not found!";
            exit;
        }

        $orderItems = $this->orderModel->getOrderItems($orderId);

        $orderTotal = 0;
        foreach ($orderItems as &$item) {
            $item['total'] = $item['quantity'] * $item['price'];
            $orderTotal += $item['total'];
        }

        $orderTotalFormatted = number_format($orderTotal, 2);

        $pdf = new \Fpdf\Fpdf();
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(190, 10, 'Order Details', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', 'B', 12);

        // Print titles
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Order ID: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['order_id'], 0, 1);

        // Repeat for other fields
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Order Name: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['order_name'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Description: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['description'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Order Date: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['formatted_order_date'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Status: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['status'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Supplier: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['supplier_name'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Ordered By: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['user_name'], 0, 1);

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50, 10, 'Last Updated: ', 0, 0);
        $pdf->Ln(6);
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX($pdf->GetX() + 10);
        $pdf->Cell(50, 10, $order['updated_on'], 0, 1);

        $pdf->Ln(10);

        // Order items section
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(190, 10, 'Order Items', 0, 1, 'C');
        $pdf->Ln(6);
        
        
        $tableWidth = 60 + 30 + 30 + 30;
        $xOffset = ($pdf->GetPageWidth() - $tableWidth) / 2;
        $pdf->SetX($xOffset);

        // Table header
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Product Name', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Price', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Total', 1, 1, 'C');

        // Table content
        $pdf->SetFont('Arial', '', 12);
        $rowCount = 0;
        foreach ($orderItems as $item) {
            if ($rowCount % 2 == 0) {
                $pdf->SetFillColor(216,191,216); 
            } else {
                $pdf->SetFillColor(173,216,230);
            }

            $pdf->SetX($xOffset);

            $pdf->Cell(60, 10, $item['product_name'], 1, 0, 'C', true);
            $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C', true);
            $pdf->Cell(30, 10, 'PHP ' . number_format($item['price'], 2), 1, 0, 'C', true);
            $pdf->Cell(40, 10, 'PHP ' . number_format($item['total'], 2), 1, 1, 'C', true);
            $rowCount++;
        }

        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetX(50);
        $pdf->Cell(40, 10, 'Total Price: ', 0);
        $pdf->SetTextColor(46, 139, 87);
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(70, 10, 'PHP ' . $orderTotalFormatted, 0, 1, 'R');

        $pdf->Output('D', 'order_' . $orderId . '_details.pdf');
        exit;
    }

}
