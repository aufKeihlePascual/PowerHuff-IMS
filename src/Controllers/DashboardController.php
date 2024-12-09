<?php

namespace App\Controllers;

class DashboardController extends BaseController
{
    protected $userModel, $adminModel;
    
    protected $categoryModel, $productCategoryModel, $productModel, $productItemModel;

    public function __construct()
    {
        $this->userModel = new \App\Models\User();
        $this->adminModel = new \App\Models\Admin();
        $this->categoryModel = new \App\Models\Category();
        $this->productCategoryModel = new \App\Models\ProductCategory();
        $this->productModel = new \App\Models\Product();
        $this->productItemModel = new \App\Models\ProductItem();
    }

    public function showDashboard()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }
        
        $role = $_SESSION['role'];

        $dashboardContent = '
            <div class="welcome-box">
                <h2>Hello, ' . htmlspecialchars($_SESSION['first_name']) . ' ' . htmlspecialchars($_SESSION['last_name']) . '!</h2>
            </div>
        ';

        $totalCategories = $this->categoryModel->totalCountCategories();
        $totalProductCategories = $this->productCategoryModel->totalCountProductCategories();
        $totalProducts = $this->productModel->totalCountProducts();
        $totalProductItems = $this->productItemModel->totalCountProductItems();
        $productCountsByCategory = $this->productModel->getProductCountsByCategory();

        $productLabels = array_column($productCountsByCategory, 'category_name');
        $productData = array_column($productCountsByCategory, 'total_products');

        $productItemsPerCategory = $this->productModel->getTotalProductItemsPerCategory();
        $productCategoryLabels = array_column($productItemsPerCategory, 'category_name');
        $productItemData = array_column($productItemsPerCategory, 'total_product_items');

        $data = [
            'title' => ucfirst($role) . ' Dashboard',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'dashboardContent' => $dashboardContent,

            'totalCategories' => json_encode($totalCategories),
            'totalProductCategories' => json_encode($totalProductCategories),
            'totalProducts' => json_encode($totalProducts),
            'totalProductItems' => json_encode($totalProductItems),

            'productCountsLabels' => json_encode($productLabels),
            'productCountsData' => json_encode($productData),

            'productCategoryLabels' => json_encode($productCategoryLabels),
            'totalProductItemsPerCategoryData' => json_encode($productItemData),

            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('dashboard', $data);
    }

    public function index()
    {
        if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in']) {
            header('Location: /dashboard');
            exit;
        }

        return $this->render('login');
    }

}
