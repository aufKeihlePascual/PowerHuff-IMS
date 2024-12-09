<?php

namespace App\Controllers;

use App\Models\BaseModel;

class ProductController extends BaseController
{
    protected $db; 

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
        $productCategories = $this->productCategoryModel->getAllProductCategories();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_name = $_POST['product_name'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];
            $lowstock_threshold = $_POST['lowstock_threshold'];
            $supplier_id = $_POST['supplier_id'];
            $category_id = $_POST['category_id'];
            $description = $_POST['description'];

            global $conn;

            if (!$conn) {
                throw new \Exception('Database connection not established.');
            }

            $this->db = $conn;   
            
            try {
                $this->db->beginTransaction();
                
                $product_id = $this->productModel->addProduct(
                    $product_name,
                    $description,
                    $price,
                    $stock_quantity,
                    $lowstock_threshold,
                    $supplier_id,
                );

            $this->productModel->mapProductToCategory($product_id, $category_id);

            $this->db->commit();

            $_SESSION['success_message'] = "Product added successfully.";
            } catch (\Exception $e) {
                $this->db->rollBack();
                $_SESSION['error_message'] = "Failed to add product: " . $e->getMessage();
            }

            header('Location: /dashboard/products');
            exit;
        }

        $suppliers = $this->supplierModel->getDistinctSuppliers();
        $categories = $this->categoryModel->getAllCategories();

        $role = $_SESSION['role'];

        $data = [
            'title' => 'Add New Product',
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
        $suppliers = $this->supplierModel->getDistinctSuppliers();
        $categories = $this->categoryModel->getAllCategories();

        foreach ($suppliers as &$supplier) {
            $supplier['selected'] = $supplier['supplier_id'] == $product['Supplier_ID'];
        }
        
        foreach ($categories as &$category) {
            $category['selected'] = $category['category_id'] == $product['Category_ID'];
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

    // public function updateStock($product_id)
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $change_type = $_POST['change_type'];
    //         $change_quantity = $_POST['change_quantity'];
    //         $user_id = $_SESSION['user_id'];

    //         $result = $this->productModel->logStockActivity($product_id, $change_type, $change_quantity, $user_id);

    //         if ($result) {
    //             $_SESSION['success_message'] = "Stock updated successfully.";
    //         } else {
    //             $_SESSION['error_message'] = "Failed to update stock.";
    //         }

    //         header('Location: /dashboard/products');
    //         exit;
    //     }

    // }

}
