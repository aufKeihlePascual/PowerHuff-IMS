<?php

namespace App\Models;

class InventoryManager extends BaseModel
{
    public function getAllProducts()
    {
        global $conn;

        $stmt = $conn->query("
            SELECT 
                p.Product_ID,
                p.Product_Name,
                p.Description AS Product_Description,
                p.Price,
                p.Stock_Quantity,
                p.Lowstock_Threshold,
                s.Supplier_Name,
                p.Created_On,
                c.Name AS Category_Name,
                pc.Name AS Product_Category_Name
            FROM 
                powerhuff_db.PRODUCTS p
            LEFT JOIN 
                powerhuff_db.PRODUCT_CATEGORY pc ON p.Product_ID = pc.Product_ID
            LEFT JOIN 
                powerhuff_db.CATEGORIES c ON pc.Category_ID = c.Category_ID
            LEFT JOIN
                powerhuff_db.SUPPLIERS s ON p.Supplier_ID = s.Supplier_ID
        ");

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public function addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO powerhuff_db.PRODUCTS (Product_Name, Price, Stock_Quantity, Lowstock_Threshold, Supplier_ID, Category_ID, Created_On)
                                VALUES (:product_name, :price, :stock_quantity, :lowstock_threshold, :supplier_id, :category_id, CURRENT_TIMESTAMP)");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE powerhuff_db.PRODUCTS 
                                SET Product_Name = :product_name, 
                                    Price = :price, 
                                    Stock_Quantity = :stock_quantity, 
                                    Lowstock_Threshold = :lowstock_threshold, 
                                    Supplier_ID = :supplier_id,
                                    Category_ID = :category_id
                                WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM powerhuff_db.PRODUCTS WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getAllCategories()
    {
        global $conn;

        $stmt = $conn->query("SELECT Category_ID, Name, Created_On FROM powerhuff_db.CATEGORIES");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addCategory($category_name, $description)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO powerhuff_db.CATEGORIES (Category_Name, Description, Created_On)
                                VALUES (:category_name, :description, CURRENT_TIMESTAMP)");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function updateCategory($id, $category_name, $description)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE powerhuff_db.CATEGORIES 
                                SET Category_Name = :category_name, 
                                    Description = :description
                                WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function deleteCategory($id)
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM powerhuff_db.CATEGORIES WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getAllProductItems()
    {
        global $conn;

        $stmt = $conn->query("SELECT p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On 
                              FROM powerhuff_db.PRODUCT_ITEMS pi
                              JOIN powerhuff_db.PRODUCTS p ON pi.Product_ID = p.Product_ID");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    public function getProductsByCategory($category_id)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM powerhuff_db.PRODUCTS WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLowStockProducts()
    {
        global $conn;

        $stmt = $conn->query("SELECT * FROM powerhuff_db.PRODUCTS WHERE Stock_Quantity <= Lowstock_Threshold");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductById($id)
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT 
            p.Product_ID,
            p.Product_Name,
            p.Description AS Product_Description,
            p.Price,
            p.Stock_Quantity,
            p.Lowstock_Threshold,
            p.Supplier_ID,
            s.Supplier_Name,
            p.Created_On,
            c.Category_ID,
            c.Name AS Category_Name,
            pc.Name AS Product_Category_Name
        FROM 
            powerhuff_db.PRODUCTS p
        LEFT JOIN 
            powerhuff_db.PRODUCT_CATEGORY pc ON p.Product_ID = pc.Product_ID
        LEFT JOIN 
            powerhuff_db.CATEGORIES c ON pc.Category_ID = c.Category_ID
        LEFT JOIN
            powerhuff_db.SUPPLIERS s ON p.Supplier_ID = s.Supplier_ID
        WHERE
            p.Product_ID = :id
    ");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public function getAllSuppliers()
{
    global $conn;

    $stmt = $conn->query("SELECT Supplier_ID, Supplier_Name FROM powerhuff_db.SUPPLIERS");
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public function getCategoryById($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT Category_ID, Name, Description, Created_On FROM powerhuff_db.CATEGORIES WHERE Category_ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public function addProductItem($product_id, $price, $stock_quantity)
{
    global $conn;

    $stmt = $conn->prepare("INSERT INTO powerhuff_db.PRODUCT_ITEMS (Product_ID, Price, Stock_Quantity, Created_On)
                            VALUES (:product_id, :price, :stock_quantity, CURRENT_TIMESTAMP)");
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock_quantity', $stock_quantity);

    return $stmt->execute();
}

public function updateProductItem($id, $product_id, $price, $stock_quantity)
{
    global $conn;

    $stmt = $conn->prepare("UPDATE powerhuff_db.PRODUCT_ITEMS 
                            SET Product_ID = :product_id, 
                                Price = :price, 
                                Stock_Quantity = :stock_quantity
                            WHERE Item_ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(':price', $price);
    $stmt->bindParam(':stock_quantity', $stock_quantity);

    return $stmt->execute();
}

public function getProductItemById($id)
{
    global $conn;

    $stmt = $conn->prepare("SELECT pi.Item_ID, pi.Product_ID, p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On 
                            FROM powerhuff_db.PRODUCT_ITEMS pi
                            JOIN powerhuff_db.PRODUCTS p ON pi.Product_ID = p.Product_ID
                            WHERE pi.Item_ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    return $stmt->fetch(\PDO::FETCH_ASSOC);
}
}