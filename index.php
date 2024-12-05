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
    $router->get('/api/products', '\App\Controllers\InventoryManagerController@getProducts');
    $router->post('/api/products', '\App\Controllers\InventoryManagerController@addProduct');
    $router->put('/api/products/(\d+)', '\App\Controllers\InventoryManagerController@updateProduct');
    $router->delete('/api/products/(\d+)', '\App\Controllers\InventoryManagerController@deleteProduct');

    $router->run();
} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage()
    ]);
}

