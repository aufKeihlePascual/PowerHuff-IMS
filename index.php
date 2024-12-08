<?php

require "vendor/autoload.php";
require "init.php";

global $conn;

try {
    $router = new \Bramus\Router\Router();

    $router->get('/', '\App\Controllers\DashboardController@index');
    
    # LOGIN
    $router->get('/login', '\App\Controllers\LoginController@showLoginForm');
    $router->post('/login', '\App\Controllers\LoginController@login');
    $router->get('/logout', '\App\Controllers\LoginController@logout');

    # DASHBOARD
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    
    # USER MANAGEMENT
    $router->get('/dashboard/users', '\App\Controllers\AdminController@showUserManagement');
    $router->post('/create-user', '\App\Controllers\AdminController@createUser');
    $router->get('/edit-user/{\d+}', '\App\Controllers\AdminController@showEditUserPage');
    $router->post('/edit-user/{\d+}', '\App\Controllers\AdminController@updateUser');
    $router->post('/delete-user/(\d+)', '\App\Controllers\AdminController@deleteUser');

    # PRODUCT MANAGEMENT VIEWS
    $router->get('/dashboard/products', '\App\Controllers\ProductController@showAllProducts');
    $router->get('/dashboard/categories', '\App\Controllers\CategoryController@showCategories');
    $router->get('/dashboard/product-categories', '\App\Controllers\ProductcategoryController@showProductCategories');
    $router->get('/dashboard/product-items', '\App\Controllers\ProductItemController@showProductItems');
    
    # PRODUCT MANAGEMENT
    $router->get('/add-product', '\App\Controllers\ProductController@addProduct');
    $router->post('/add-product', '\App\Controllers\ProductController@addProduct');
    $router->get('/edit-product/(\d+)', '\App\Controllers\ProductController@editProduct');
    $router->post('/edit-product/(\d+)', '\App\Controllers\ProductController@editProduct');
    $router->post('/delete-product/(\d+)', '\App\Controllers\ProductController@deleteProduct');

    # PRODUCT ITEMS MANAGEMENT
    $router->get('/edit-product-item/(\d+)', '\App\Controllers\ProductItemController@editProductItem');
    $router->post('/edit-product-item/(\d+)', '\App\Controllers\ProductItemController@editProductItem');

    # CATEGORY MANAGEMENT
    $router->get('/add-category', '\App\Controllers\CategoryController@addCategory');
    $router->post('/add-category', '\App\Controllers\CategoryController@addCategory');
    $router->get('/edit-category/(\d+)', '\App\Controllers\CategoryController@editCategory');
    $router->post('/edit-category/(\d+)', '\App\Controllers\CategoryController@editCategory');

    // $router->post('/delete-category/(\d+)', '\App\Controllers\InventoryManagerController@deleteCategory');

    # PRODUCT CATEGORY MANAGEMENT
    $router->get('/add-product-category', '\App\Controllers\ProductCategoryController@addProductCategory');
    $router->post('/add-product-category', '\App\Controllers\ProductCategoryController@addProductCategory'); 
    $router->get('/dashboard/edit-product-category/(\d+)', '\App\Controllers\ProductCategoryController@editProductCategory');
    $router->post('/dashboard/edit-product-category/(\d+)', '\App\Controllers\ProductCategoryController@editProductCategory');

    # PRODUCT ITEM MANAGEMENT
    $router->get('/add-product-item', '\App\Controllers\ProductItemController@addProductItem');
    $router->post('/add-product-item', '\App\Controllers\ProductItemController@addProductItem');
    $router->get('/edit-product-item/(\d+)', '\App\Controllers\ProductItemController@editProductItem');
    $router->post('/edit-product-item/(\d+)', '\App\Controllers\ProductItemController@editProductItem');
        
    # SUPPLIER MANAGEMENT
    $router->get('/dashboard/suppliers', '\App\Controllers\SupplierController@showSupplierManagement');
    
    $router->get('/add-supplier', '\App\Controllers\SupplierController@showAddSupplierPage');
    $router->post('/create-supplier', '\App\Controllers\SupplierController@createSupplier');
    $router->post('/update-supplier/(\d+)', '\App\Controllers\SupplierController@updateSupplier');
    $router->post('/delete-supplier/(\d+)', '\App\Controllers\SupplierController@deleteSupplier');
    
    # ORDER MANAGEMENT
    $router->get('/dashboard/orders', '\App\Controllers\OrderController@showOrdersPage');
    $router->post('/create-order', '\App\Controllers\OrderController@createOrder');
    $router->get('/view-order/{id}', '\App\Controllers\OrderController@showOrderDetails');

    $router->get('/edit-order/{\d+}', '\App\Controllers\OrderController@showEditOrderPage');
    $router->post('/edit-order/{\d+}', '\App\Controllers\OrderController@updateOrder');
    $router->post('/delete-order/{\d+}', '\App\Controllers\OrderController@deleteOrder');

    $router->post('/generate-pdf-order', '\App\Controllers\OrderController@generatePdf');

    # ACTIVITIES
    $router->get('/dashboard/product-activity', '\App\Controllers\ActivityController@showProductActivity');
    $router->get('/dashboard/stock-activity', '\App\Controllers\ActivityController@showStockActivity');

    $router->post('/generate-pdf/{type}', function ($type) {
        $controller = new \App\Controllers\GeneratePDFController();
        $controller->handlePdfRequest($type);
    });


    
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

