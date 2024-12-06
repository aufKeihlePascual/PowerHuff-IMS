<?php

require "vendor/autoload.php";
require "init.php";

global $conn;

try {
    $router = new \Bramus\Router\Router();

    $router->get('/', '\App\Controllers\HomeController@index');
    
    # LOGIN
    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    # DASHBOARD
    $router->get('/dashboard', '\App\Controllers\AdminController@showDashboard');
    $router->get('/dashboard/users', '\App\Controllers\AdminController@showUserManagement');

    # USER MANAGEMENT
    $router->post('/create-user', '\App\Controllers\AdminController@createUser');
    $router->get('/edit-user/{\d+}', '\App\Controllers\AdminController@showEditUserPage');
    $router->post('/edit-user/{\d+}', '\App\Controllers\AdminController@updateUser');
    $router->post('/delete-user/(\d+)', '\App\Controllers\AdminController@deleteUser');

    # PRODUCT MANAGEMENT VIEWS
    $router->get('/dashboard/products', '\App\Controllers\InventoryManagerController@showAllProducts');
    $router->get('/dashboard/categories', '\App\Controllers\InventoryManagerController@showCategories');
    $router->get('/dashboard/product-categories', '\App\Controllers\InventoryManagerController@showProductCategories');
    $router->get('/dashboard/product-items', '\App\Controllers\InventoryManagerController@showProductItems');
    
    # PRODUCT MANAGEMENT
    $router->get('/add-product', '\App\Controllers\InventoryManagerController@addProduct');
    $router->post('/add-product', '\App\Controllers\InventoryManagerController@addProduct');
    $router->get('/edit-product/(\d+)', '\App\Controllers\InventoryManagerController@editProduct');
    $router->post('/edit-product/(\d+)', '\App\Controllers\InventoryManagerController@editProduct');
    // $router->post('/delete-product/(\d+)', '\App\Controllers\InventoryManagerController@deleteProduct');

    # CATEGORY MANAGEMENT
    $router->get('/add-category', '\App\Controllers\InventoryManagerController@addCategory');
    $router->post('/add-category', '\App\Controllers\InventoryManagerController@addCategory');
    $router->get('/edit-category/(\d+)', '\App\Controllers\InventoryManagerController@editCategory');
    $router->post('/edit-category/(\d+)', '\App\Controllers\InventoryManagerController@editCategory');
    // $router->post('/delete-category/(\d+)', '\App\Controllers\InventoryManagerController@deleteCategory');

    # PRODUCT ITEM MANAGEMENT
    $router->get('/add-product-item', '\App\Controllers\InventoryManagerController@addProductItem');
    $router->post('/add-product-item', '\App\Controllers\InventoryManagerController@addProductItem');
    $router->get('/edit-product-item/(\d+)', '\App\Controllers\InventoryManagerController@editProductItem');
    $router->post('/edit-product-item/(\d+)', '\App\Controllers\InventoryManagerController@editProductItem');
        
    # SUPPLIER MANAGEMENT
    $router->get('/dashboard/suppliers', '\App\Controllers\SupplierController@showSupplierManagement');
    
    $router->get('/add-supplier', '\App\Controllers\SupplierController@showAddSupplierPage');
    $router->post('/create-supplier', '\App\Controllers\SupplierController@createSupplier');
    $router->post('/update-supplier/(\d+)', '\App\Controllers\SupplierController@updateSupplier');
    $router->post('/delete-supplier/(\d+)', '\App\Controllers\SupplierController@deleteSupplier');
    
    $router->get('/reset-login-attempts', function () {
        $_SESSION['login_attempts'] = 0;
        echo "Login attempts reset.";
    });

    $router->run();

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}

