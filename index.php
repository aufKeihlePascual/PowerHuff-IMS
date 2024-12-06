<?php

require "vendor/autoload.php";
require "init.php";

global $conn;

try {
    $router = new \Bramus\Router\Router();

    $router->get('/', '\App\Controllers\HomeController@index');

    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    # Dashboard
    $router->get('/admin-dashboard', '\App\Controllers\AdminController@showDashboard');
    $router->get('/inventory-manager-dashboard', '\App\Controllers\InventoryManagerController@showDashboard');
    $router->get('/procurement-manager-dashboard', '\App\Controllers\ProcurementManagerController@showDashboard');

    $router->get('/dashboard/users', '\App\Controllers\AdminController@showUserManagement');
    $router->post('/create-user', '\App\Controllers\AdminController@createUser');
    $router->post('/delete-user/(\d+)', '\App\Controllers\AdminController@deleteUser');
    $router->get('/edit-user/{\d+}', '\App\Controllers\AdminController@showEditUserPage');
    $router->post('/edit-user/{\d+}', '\App\Controllers\AdminController@updateUser');

    $router->get('/reset-login-attempts', function () {
        $_SESSION['login_attempts'] = 0;
        echo "Login attempts reset.";
    });
    

    # Inventory Manager
    $router->get('/inventory-manager-dashboard', '\App\Controllers\InventoryManagerController@showDashboard');
    $router->get('/dashboard/products', '\App\Controllers\InventoryManagerController@showAllProducts');
    $router->get('/dashboard/categories', '\App\Controllers\InventoryManagerController@showCategories');
    $router->get('/dashboard/product-items', '\App\Controllers\InventoryManagerController@showProductItems');

    # Product
    $router->get('/add-product', '\App\Controllers\InventoryManagerController@addProduct');
    $router->post('/add-product', '\App\Controllers\InventoryManagerController@addProduct');
    $router->get('/edit-product/(\d+)', '\App\Controllers\InventoryManagerController@editProduct');
    $router->post('/edit-product/(\d+)', '\App\Controllers\InventoryManagerController@editProduct');
    // $router->post('/delete-product/(\d+)', '\App\Controllers\InventoryManagerController@deleteProduct');

    # Categories
    $router->get('/add-category', '\App\Controllers\InventoryManagerController@addCategory');
    $router->post('/add-category', '\App\Controllers\InventoryManagerController@addCategory');
    $router->get('/edit-category/(\d+)', '\App\Controllers\InventoryManagerController@editCategory');
    $router->post('/edit-category/(\d+)', '\App\Controllers\InventoryManagerController@editCategory');
    // $router->post('/delete-category/(\d+)', '\App\Controllers\InventoryManagerController@deleteCategory');

    # Product Items
    $router->get('/add-product-item', '\App\Controllers\InventoryManagerController@addProductItem');
    $router->post('/add-product-item', '\App\Controllers\InventoryManagerController@addProductItem');
    $router->get('/edit-product-item/(\d+)', '\App\Controllers\InventoryManagerController@editProductItem');
    $router->post('/edit-product-item/(\d+)', '\App\Controllers\InventoryManagerController@editProductItem');
        
    # Suppliers
    $router->get('/dashboard/suppliers', '\App\Controllers\SupplierController@showSupplierManagement');
    // In your routing or controller logic
$router->get('/add-supplier', 'SupplierController@showAddSupplierForm');

    $router->post('/create-supplier', '\App\Controllers\SupplierController@createSupplier');
    $router->post('/update-supplier/(\d+)', '\App\Controllers\SupplierController@updateSupplier');
    $router->post('/delete-supplier/(\d+)', '\App\Controllers\SupplierController@deleteSupplier');
    $router->run();
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}

