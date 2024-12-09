<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    protected $productModel, $categoryModel, $supplierModel, $productCategoryModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\Product();
        $this->productCategoryModel = new \App\Models\ProductCategory();
        $this->categoryModel = new \App\Models\Category();
        $this->supplierModel = new \App\Models\Supplier();
    }

    public function showAllProducts()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $products = $this->productModel->getAllProducts();
        $productCategories = $this->productCategoryModel->getAllProductCategories();
        $role = $_SESSION['role'];

        $data = [
            'title' => 'Products',
            'username' => $_SESSION['username'],
            'products' => $products,
            'productCategories' => $productCategories,
            
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('products', $data);
    }

    public function addProduct()
    {
        $suppliers = $this->supplierModel->getDistinctSuppliers();
        $categories = $this->categoryModel->getAllCategories();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_name = $_POST['product_name'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $lowstock_threshold = $_POST['lowstock_threshold'];
            $supplier_id = $_POST['supplier_id'];
            $category_id = $_POST['category_id'];

            $result = $this->productModel->addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id);

            if ($result) {
                $_SESSION['success_message'] = "Product added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add product.";
            }

            header('Location: /dashboard/products');
            exit;
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Add New Product',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'suppliers' => $suppliers,
            'categories' => $categories,

            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('add-product', $data);
    }

    public function editProduct($id)
    {
        $product = $this->productModel->getProductById($id);
        $suppliers = $this->supplierModel->getAllSuppliers();
        $categories = $this->categoryModel->getAllCategories();

        // Mark selected supplier and category
        foreach ($suppliers as &$supplier) {
            $supplier['selected'] = $supplier['Supplier_ID'] == $product['Supplier_ID'];
        }

        foreach ($categories as &$category) {
            $category['selected'] = $category['Category_ID'] == $product['Category_ID'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_name = $_POST['product_name'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $lowstock_threshold = $_POST['lowstock_threshold'];
            $supplier_id = $_POST['supplier_id'];
            $category_id = $_POST['category_id'];

            $result = $this->productModel->updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id);

            if ($result) {
                $_SESSION['success_message'] = "Product updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update product.";
            }

            header('Location: /dashboard/products');
            exit;
        }

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Edit Product',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'product' => $product,
            'suppliers' => $suppliers,
            'categories' => $categories,

            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('edit-product', $data);
    }

    public function deleteProduct($id)
    {
        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            $_SESSION['success_message'] = "Product deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete product.";
        }

        header('Location: /dashboard/products');
        exit;
    }

    public function updateStock($product_id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $change_type = $_POST['change_type'];
            $change_quantity = $_POST['change_quantity'];
            $user_id = $_SESSION['user_id'];

            $result = $this->productModel->logStockActivity($product_id, $change_type, $change_quantity, $user_id);

            if ($result) {
                $_SESSION['success_message'] = "Stock updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update stock.";
            }

            header('Location: /dashboard/products');
            exit;
        }

    }

}
