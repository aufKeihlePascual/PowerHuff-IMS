<?php

namespace App\Controllers;

class CategoryController extends BaseController
{
    protected $categoryModel, $productCategoryModel;

    public function __construct()
    {
        $this->categoryModel = new \App\Models\Category();
        $this->productCategoryModel = new \App\Models\ProductCategory();
    }

    public function showCategories()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Categories',
            'username' => $_SESSION['username'],
            'categories' => $categories,
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('categories', $data);
    }

    public function addCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_name = $_POST['category_name'];
            $description = $_POST['description'];

            $result = $this->categoryModel->addCategory($category_name, $description);

            if ($result) {
                $_SESSION['success_message'] = "Category added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add category.";
            }

            header('Location: /dashboard/categories');
            exit;
        }

        $role = $_SESSION['role'];
        
        $data = [
            'title' => 'Add New Category',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('add-category', $data);
    }

    public function deleteCategory($id)
    {
        $result = $this->categoryModel->deleteCategory($id);

        if ($result) {
            $_SESSION['success_message'] = "Category deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete category.";
        }

        header('Location: /dashboard/categories');
        exit;
    }

    public function editCategory($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_name = $_POST['category_name'];
            $description = $_POST['description'];

            // Update the category in the database
            $result = $this->categoryModel->updateCategory($id, $category_name, $description);

            if ($result) {
                $_SESSION['success_message'] = "Category updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update category.";
            }

            // Redirect to the categories page
            header('Location: /dashboard/categories');
            exit;
        }

        // If GET request, fetch category details and render the edit page
        $category = $this->categoryModel->getCategoryById($id);
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Edit Category',
            'category' => $category,
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('edit-category', $data);
    }
}
