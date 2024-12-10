<?php

namespace App\Controllers;

class ProductItemController extends BaseController
{
    protected $productModel, $productItemModel, $categoryModel;

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

    public function editProductItem($id)
    {
        error_log("Entering editProductItem method with ID: " . $id);
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                error_log("Processing POST request for edit-product-item");
                $product_id = $_POST['product_id'];
                $size = $_POST['size'];
                $color = $_POST['color'];
                $price = $_POST['price'];
                $stock_quantity = $_POST['stock_quantity'];

                $result = $this->productItemModel->updateProductItem($id, $product_id, $size, $color, $price, $stock_quantity);

                if ($result) {
                    $_SESSION['success_message'] = "Product item updated successfully.";
                } else {
                    $_SESSION['error_message'] = "Failed to update product item.";
                }

                header('Location: /dashboard/product-items');
                exit;
            }

            error_log("Processing GET request for edit-product-item");
            $productItem = $this->productItemModel->getProductItemById($id);
            if (!$productItem) {
                error_log("Product item not found for ID: " . $id);
                $_SESSION['error_message'] = "Product item with ID {$id} not found.";
                header('Location: /dashboard/product-items');
                exit;
            }

            $products = $this->productModel->getAllProducts();
            foreach ($products as &$product) {
                $product['selected'] = ($product['Product_ID'] == $productItem['product_id']);
            }

            $data = [
                'title' => 'Edit Product Item',
                'productItem' => $productItem,
                'products' => $products,
                'role' => $_SESSION['role'],
                'isAdmin' => $_SESSION['role'] === 'Admin',
                'isInventoryManager' => $_SESSION['role'] === 'Inventory_Manager',
                'isProcurementManager' => $_SESSION['role'] === 'Procurement_Manager',
                'canAccessLinks' => in_array($_SESSION['role'], ['Admin', 'Inventory_Manager']),
            ];

            error_log("Rendering edit-product-item view with data: " . json_encode($data));
            return $this->render('edit-product-item', $data);

        } catch (\Exception $e) {
            error_log("Exception in editProductItem: " . $e->getMessage());
            $_SESSION['error_message'] = "An unexpected error occurred.";
            header('Location: /dashboard/product-items');
            exit;
        }
    }

    public function addProductItem()
{
    try {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $size = $_POST['size'] ?? null; // Handle nullable size
            $color = $_POST['color'] ?? null; // Handle nullable color
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            $result = $this->productItemModel->addProductItem($product_id, $size, $color, $price, $stock_quantity);

            if ($result) {
                $_SESSION['success_message'] = "Product item added successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to add product item.";
            }

            header('Location: /dashboard/product-items');
            exit;
        }

        $products = $this->productModel->getAllProducts();

        $data = [
            'title' => 'Add Product Item',
            'products' => $products,
            'role' => $_SESSION['role'],
            'isAdmin' => $_SESSION['role'] === 'Admin',
            'isInventoryManager' => $_SESSION['role'] === 'Inventory_Manager',
            'isProcurementManager' => $_SESSION['role'] === 'Procurement_Manager',
            'canAccessLinks' => in_array($_SESSION['role'], ['Admin', 'Inventory_Manager']),
        ];

        return $this->render('add-product-item', $data);

    } catch (\Exception $e) {
        error_log("Exception in addProductItem: " . $e->getMessage());
        $_SESSION['error_message'] = "An unexpected error occurred.";
        header('Location: /dashboard/product-items');
        exit;
    }
}

        public function deleteProductItem($product_item_id)
        {
            $result = $this->productItemModel->deleteProductItem($product_item_id);

            if ($result) {
                $_SESSION['success_message'] = "Product Item deleted successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to delete product item.";
            }

            header('Location: /dashboard/product-items');
            exit;
        }
}