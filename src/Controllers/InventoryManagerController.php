<?php

namespace App\Controllers;

use App\Models\InventoryManager;

class InventoryManagerController extends BaseController
{
    private $inventoryManager;

    public function __construct()
    {
        $this->inventoryManager = new InventoryManager();
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

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

        $data = [
            'title' => 'Inventory Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'dashboardContent' => $dashboardContent,
        ];

        return $this->render('inventory-manager-dashboard', $data);
    }

    public function getProducts()
    {
        $products = $this->inventoryManager->getAllProducts();
        header('Content-Type: application/json');
        echo json_encode($products);
    }

    public function addProduct()
    {
        $product_name = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $lowstock_threshold = $_POST['lowstock_threshold'] ?? 0;
        $supplier_id = $_POST['supplier_id'] ?? 0;

        $result = $this->inventoryManager->addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id);

        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
    }

    public function updateProduct($id)
    {
        $product_name = $_POST['product_name'] ?? '';
        $price = $_POST['price'] ?? 0;
        $stock_quantity = $_POST['stock_quantity'] ?? 0;
        $lowstock_threshold = $_POST['lowstock_threshold'] ?? 0;
        $supplier_id = $_POST['supplier_id'] ?? 0;

        $result = $this->inventoryManager->updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id);

        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
    }

    public function deleteProduct($id)
    {
        $result = $this->inventoryManager->deleteProduct($id);

        header('Content-Type: application/json');
        echo json_encode(['success' => $result]);
    }
}

