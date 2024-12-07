<?php

namespace App\Controllers;

class ProductCategoryController extends BaseController
{
    protected $productCategoryModel;

    public function __construct()
    {
        $this->productCategoryModel = new \App\Models\ProductCategory();
    }

    public function showProductCategories()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $product_categories = $this->productCategoryModel->getAllProductCategories();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Product Categories',
            'username' => $_SESSION['username'],
            'product_categories' => $product_categories,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];
    
        return $this->render('product-categories', $data);
    }

    
}
