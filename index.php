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
    $router->get('/admin-dashboard', '\App\Controllers\DashboardController@showDashboard');
    $router->get('/dashboard/users', '\App\Controllers\DashboardController@showUserManagement');
    $router->post('/create-user', '\App\Controllers\AdminController@createUser');
    $router->post('/delete-user/(\d+)', '\App\Controllers\AdminController@deleteUser');

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

