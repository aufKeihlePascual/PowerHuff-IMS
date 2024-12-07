<?php

namespace App\Controllers;

class CategoryController extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new \App\Models\Category();
    }

    public function showCategories()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'title' => 'Categories',
            'username' => $_SESSION['username'],
            'categories' => $categories,
        ];
    
        return $this->render('categories', $data);
    }

    public function showProductCategories()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $product_categories = $this->categoryModel->getAllProductCategories();

        $data = [
            'title' => 'Product Categories',
            'username' => $_SESSION['username'],
            'product_categories' => $product_categories,
        ];
    
        return $this->render('product-categories', $data);
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

        $data = [
            'title' => 'Add New Category',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
        ];

        return $this->render('add-category', $data);
    }

    public function editCategory($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $category_name = $_POST['category_name'];
            $description = $_POST['description'];

            $result = $this->categoryModel->updateCategory($id, $category_name, $description);

            if ($result) {
                $_SESSION['success_message'] = "Category updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update category.";
            }

            header('Location: /dashboard/categories');
            exit;
        }

        $category = $this->categoryModel->getCategoryById($id);

        $data = [
            'title' => 'Edit Category',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'category' => $category,
        ];

        return $this->render('edit-category', $data);
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

}
