<?php

namespace App\Controllers;

class ProductController extends BaseController
{
    protected $productModel, $productItemModel, $categoryModel, $supplierModel;

    public function __construct()
    {
        $this->productModel = new \App\Models\Product();
        $this->categoryModel = new \App\Models\Category();
        $this->productItemModel = new \App\Models\ProductItem();
    }

    public function showAllProducts()
    {
        if (!isset($_SESSION['is_logged_in']) || !$_SESSION['is_logged_in']) {
            header('Location: /login');
            exit;
        }

        $products = $this->productModel->getAllProducts();

        $data = [
            'title' => 'Products',
            'username' => $_SESSION['username'],
            'products' => $products,
        ];

        return $this->render('products', $data);
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

    public function addProduct()
    {
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

        $suppliers = $this->supplierModel->getAllSuppliers();
        $categories = $this->categoryModel->getAllCategories();

        $data = [
            'title' => 'Add New Product',
            'first_name' => $_SESSION['first_name'],
            'last_name' => $_SESSION['last_name'],
            'username' => $_SESSION['username'],
            'suppliers' => $suppliers,
            'categories' => $categories,
        ];

        return $this->render('add-product', $data);
    }

public function editProduct($id)
{
    $product = $this->productModel->getProductById($id);
    $suppliers = $this->supplierModel->getAllSuppliers();
    $categories = $this->categoryModel->getAllCategories();
    
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

    $data = [
        'title' => 'Edit Product',
        'first_name' => $_SESSION['first_name'],
        'last_name' => $_SESSION['last_name'],
        'username' => $_SESSION['username'],
        'product' => $product,
        'suppliers' => $suppliers,
        'categories' => $categories,
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

    // Mark the current product as selected
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
