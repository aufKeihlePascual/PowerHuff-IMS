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

    public function addProduct()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $lowstock_threshold = $_POST['lowstock_threshold'];
        $supplier_id = $_POST['supplier_id'];
        $category_id = $_POST['category_id'];

        $result = $this->inventoryManager->addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id);

        if ($result) {
            $_SESSION['success_message'] = "Product added successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to add product.";
        }

        header('Location: /dashboard/products');
        exit;
    }

    $suppliers = $this->inventoryManager->getAllSuppliers();
    $categories = $this->inventoryManager->getAllCategories();

    $data = [
        'title' => 'Add New Product',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'suppliers' => $suppliers,
        'categories' => $categories,
    ];

    return $this->render('add-product', $data);
}

public function editProduct($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_name = $_POST['product_name'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];
        $lowstock_threshold = $_POST['lowstock_threshold'];
        $supplier_id = $_POST['supplier_id'];
        $category_id = $_POST['category_id'];

        $result = $this->inventoryManager->updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id);

        if ($result) {
            $_SESSION['success_message'] = "Product updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update product.";
        }

        header('Location: /dashboard/products');
        exit;
    }

    $product = $this->inventoryManager->getProductById($id);
    $suppliers = $this->inventoryManager->getAllSuppliers();
    $categories = $this->inventoryManager->getAllCategories();

    $data = [
        'title' => 'Edit Product',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'product' => $product,
        'suppliers' => $suppliers,
        'categories' => $categories,
    ];

    return $this->render('edit-product', $data);
}

public function deleteProduct($id)
{
    $result = $this->inventoryManager->deleteProduct($id);

    if ($result) {
        $_SESSION['success_message'] = "Product deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete product.";
    }

    header('Location: /dashboard/products');
    exit;
}

public function addCategory()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_name = $_POST['category_name'];
        $description = $_POST['description'];

        $result = $this->inventoryManager->addCategory($category_name, $description);

        if ($result) {
            $_SESSION['success_message'] = "Category added successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to add category.";
        }

        header('Location: /dashboard/categories');
        exit;
    }

    $data = [
        'title' => 'Add New Category',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
    ];

    return $this->render('add-category', $data);
}

public function editCategory($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $category_name = $_POST['category_name'];
        $description = $_POST['description'];

        $result = $this->inventoryManager->updateCategory($id, $category_name, $description);

        if ($result) {
            $_SESSION['success_message'] = "Category updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update category.";
        }

        header('Location: /dashboard/categories');
        exit;
    }

    $category = $this->inventoryManager->getCategoryById($id);

    $data = [
        'title' => 'Edit Category',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'category' => $category,
    ];

    return $this->render('edit-category', $data);
}

public function deleteCategory($id)
{
    $result = $this->inventoryManager->deleteCategory($id);

    if ($result) {
        $_SESSION['success_message'] = "Category deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Failed to delete category.";
    }

    header('Location: /dashboard/categories');
    exit;
}

public function addProductItem()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];

        $result = $this->inventoryManager->addProductItem($product_id, $price, $stock_quantity);

        if ($result) {
            $_SESSION['success_message'] = "Product item added successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to add product item.";
        }

        header('Location: /dashboard/product-items');
        exit;
    }

    $products = $this->inventoryManager->getAllProducts();

    $data = [
        'title' => 'Add New Product Item',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'products' => $products,
    ];

    return $this->render('add-product-item', $data);
}

public function editProductItem($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product_id = $_POST['product_id'];
        $price = $_POST['price'];
        $stock_quantity = $_POST['stock_quantity'];

        $result = $this->inventoryManager->updateProductItem($id, $product_id, $price, $stock_quantity);

        if ($result) {
            $_SESSION['success_message'] = "Product item updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update product item.";
        }

        header('Location: /dashboard/product-items');
        exit;
    }

    $productItem = $this->inventoryManager->getProductItemById($id);
    $products = $this->inventoryManager->getAllProducts();

    // Mark the current product as selected
    foreach ($products as &$product) {
        $product['selected'] = ($product['Product_ID'] == $productItem['Product_ID']);
    }

    $data = [
        'title' => 'Edit Product Item',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'productItem' => $productItem,
        'products' => $products,
    ];

    return $this->render('edit-product-item', $data);
}
}
