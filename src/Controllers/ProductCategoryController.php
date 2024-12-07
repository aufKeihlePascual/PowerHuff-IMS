<?php

namespace App\Controllers;

class ProductController extends BaseController
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

        $data = [
            'title' => 'Product Categories',
            'username' => $_SESSION['username'],
            'product_categories' => $product_categories,
        ];
    
        return $this->render('product-categories', $data);
    }

    
}
