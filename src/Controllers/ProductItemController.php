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

        $productItems = $this->productItemModel->getAllProductItems();

        $data = [
            'title' => 'Product Items',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'productItems' => $productItems,
        ];

        return $this->render('product-items', $data);
    }

    public function addProductItem()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            $result = $this->productItemModel->addProductItem($product_id, $price, $stock_quantity);

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
            'title' => 'Add New Product Item',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'products' => $products,
        ];

        return $this->render('add-product-item', $data);
    }

    public function editProductItem($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $product_id = $_POST['product_id'];
            $price = $_POST['price'];
            $stock_quantity = $_POST['stock_quantity'];

            $result = $this->productItemModel->updateProductItem($id, $product_id, $price, $stock_quantity);

            if ($result) {
                $_SESSION['success_message'] = "Product item updated successfully.";
            } else {
                $_SESSION['error_message'] = "Failed to update product item.";
            }

            header('Location: /dashboard/product-items');
            exit;
        }

        $productItem = $this->productItemModel->getProductItemById($id);
        $products = $this->productModel->getAllProducts();

        foreach ($products as &$product) {
            $product['selected'] = ($product['Product_ID'] == $productItem['Product_ID']);
        }

        $data = [
            'title' => 'Edit Product Item',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'productItem' => $productItem,
            'products' => $products,
        ];

        return $this->render('edit-product-item', $data);
    }
}
