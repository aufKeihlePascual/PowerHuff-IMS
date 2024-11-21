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
    $router->get('/dashboard', '\App\Controllers\DashboardController@showDashboard');
    $router->get('/dashboard/content', '\App\Controllers\DashboardController@loadContent');

    $router->run();
    } catch (Exception $e) {
        echo json_encode([
            'error' => $e->getMessage()
        ]);
    }

?>