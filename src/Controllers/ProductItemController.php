<?php

namespace App\Controllers;

class ProductItemController extends BaseController
{
    protected $productModel, $productItemModel, $categoryModel, $supplierModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\Product();
        $this->categoryModel = new \App\Models\Category();
        $this->productItemModel = new \App\Models\ProductItem();
    }

        public function showProductItems()
        {
            if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
                header('Location: /login');
                exit;
            }

            $product_items = $this->productItemModel->getAllProductItems();
            $role = $_SESSION['role'];

            $data = [
                'title' => 'Product Items',
                'username' => $_SESSION['username'],
                'productItems' => $product_items,
            
                'role' => $role,
                'isAdmin' => $role === 'Admin',
                'isInventoryManager' => $role === 'Inventory_Manager',
                'isProcurementManager' => $role === 'Procurement_Manager',
                'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
            ];

            return $this->render('product-items', $data);
        }


    public function addProductItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $product_id = $_POST['product_id'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            // Add product item
            $result = $this->productItemModel->addProductItem($product_id, $price, $stock_quantity);

            // Handle result
            if ($result) {
                $_SESSION['success_message'] = "Product item added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add product item.";
            }

            // Redirect to product items page
            header('Location: /dashboard/product-items');
            exit;
        }

        // Fetch all products for the dropdown
        $products = $this->productModel->getAllProducts();
        $role = $_SESSION['role'];

        // Prepare data for view
        $data = [
            'title' => 'Add New Product Item',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
            'products' => $products,  // Pass products to view
        ];

        return $this->render('add-product-item', $data);
    }
        public function editProductItem($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission (product item update)
            $product_id = $_POST['product_id'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            // Update the product item
            $result = $this->productItemModel->updateProductItem($id, $product_id, $price, $stock_quantity);

            if ($result) {
                $_SESSION['success_message'] = "Product item updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update product item.";
            }

            header('Location: /dashboard/product-items');
            exit;
        }

        // Retrieve the product item and products list
        $productItem = $this->productItemModel->getProductItemById($id);
        if (!$productItem) {
            $_SESSION['error_message'] = "Product item not found.";
            header('Location: /dashboard/product-items');
            exit;
        }

        $products = $this->productModel->getAllProducts();
        foreach ($products as &$product) {
            $product['selected'] = ($product['Product_ID'] == $productItem['Product_ID']);
        }

        // Prepare data for the view
        $role = $_SESSION['role'];
        $data = [
            'title' => 'Edit Product Item',
            'productItem' => $productItem,
            'products' => $products,
            'role' => $role,
            'isAdmin' => $role === 'Admin',
            'isInventoryManager' => $role === 'Inventory_Manager',
            'isProcurementManager' => $role === 'Procurement_Manager',
            'canAccessLinks' => in_array($role, ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('edit-product-item', $data);
    }
}
