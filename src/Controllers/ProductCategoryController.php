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

        $product_category = $this->productCategoryModel->getAllProductCategories();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Product Categories',
            'username' => $_SESSION['username'],
            'product_category' => $product_category,
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];
    
        return $this->render('product-categories', $data);
    }

    public function addProductCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_name = $_POST['category_name'];
            $category_description = $_POST['category_description'];

            // Add the product category
            $result = $this->productCategoryModel->addProductCategory($category_name, $category_description);

            if ($result) {
                $_SESSION['success_message'] = "Product category added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add product category.";
            }

            header('Location: /dashboard/product-categories');
            exit;
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Add New Product Category',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('add-product-category', $data);
    }
}
