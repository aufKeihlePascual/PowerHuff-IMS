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

    public function showAllProducts()
{
    if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
        header('Location: /login');
        exit;
    }

    $products = $this->inventoryManager->getAllProducts();

    $data = [
        'title' => 'All Products',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'products' => $products,
    ];

    return $this->render('products', $data);
}

    public function showCategories()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $categories = $this->inventoryManager->getAllCategories();

        $data = [
            'title' => 'Categories',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'categories' => $categories, 
        ];
    
        return $this->render('categories', $data);
    }

    public function showProductItems()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $productItems = $this->inventoryManager->getAllProductItems();

        $data = [
            'title' => 'Product Items',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'productItems' => $productItems,
        ];

        return $this->render('product-items', $data);
    }
}
