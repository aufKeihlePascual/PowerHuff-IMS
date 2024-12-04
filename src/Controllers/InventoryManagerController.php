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

        $data = [
            'title' => 'Inventory Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
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

