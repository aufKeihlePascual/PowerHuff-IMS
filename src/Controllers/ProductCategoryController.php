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
            // Get form data
            $category_name = trim($_POST['category_name']);
            $description = trim($_POST['description']);

            // Validate inputs (add further validation as needed)
            if (empty($category_name)) {
                $_SESSION['error_message'] = "Category name is required.";
                header('Location: /dashboard/add-product-category');
                exit;
            }

            // Insert into database
            $result = $this->productCategoryModel->insertProductCategory($category_name, $description);

            if ($result) {
                $_SESSION['success_message'] = "Product category added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add product category.";
            }

            header('Location: /dashboard/product-categories');
            exit;
        }

        // For GET requests, load the form
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


    public function editProductCategory($id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get form data
        $name = $_POST['name'];
        $description = $_POST['description'];

        // Update the database
        $result = $this->productCategoryModel->updateProductCategory($id, $name, $description);

        if ($result) {
            $_SESSION['success_message'] = "Product category updated successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to update product category.";
        }

        header('Location: /dashboard/product-categories');
        exit;
    }

    // For GET request, fetch category details
    $category = $this->productCategoryModel->getCategoryById($id);

    if (!$category) {
        $_SESSION['error_message'] = "Product category not found.";
        header('Location: /dashboard/product-categories');
        exit;
    }

    $role = $_SESSION['role'];
    $data = [
        'title' => 'Edit Category',
        'category' => $category,
        'username' => $_SESSION['username'],
        'role' => $role,
        'isAdmin' => $role === 'Admin',
        'isInventoryManager' => $role === 'Inventory_Manager',
        'isProcurementManager' => $role === 'Procurement_Manager',
        'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
    ];

    return $this->render('edit-category', $data);
}

}
