<?php

namespace App\Models;

class Product extends BaseModel
{
    public function getAllProducts()
    {
        $stmt = $this->db->query("
            SELECT 
                p.Product_ID,
                p.Product_Name,
                p.Description AS Product_Description,
                p.Price,
                p.Stock_Quantity,
                p.Lowstock_Threshold,
                CONCAT(UPPER(SUBSTRING(s.Supplier_Name, 1, 1)), LOWER(SUBSTRING(s.Supplier_Name, 2))) AS Supplier_Name, 
                p.Created_On,
                c.Name AS Category_Name,
                pc.Name AS Product_Category_Name
            FROM 
                products p
            LEFT JOIN 
                product_category pc ON p.Product_ID = pc.Product_ID
            LEFT JOIN 
                categories c ON pc.Category_ID = c.Category_ID
            LEFT JOIN
                suppliers s ON p.Supplier_ID = s.Supplier_ID
        ");

        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['Supplier_Name'] = ucwords(strtolower($product['Supplier_Name']));
        }

        return $products;
    }

    public function getProductById($id)
    {
        $stmt = $this->db->prepare("
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

    public function addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        $stmt = $this->db->prepare("
            INSERT INTO powerhuff_db.PRODUCTS (Product_Name, Price, Stock_Quantity, Lowstock_Threshold, Supplier_ID, Category_ID, Created_On)
            VALUES (:product_name, :price, :stock_quantity, :lowstock_threshold, :supplier_id, :category_id, CURRENT_TIMESTAMP)
        ");

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
        $stmt = $this->db->prepare("
            UPDATE powerhuff_db.PRODUCTS 
            SET Product_Name = :product_name, 
                Price = :price, 
                Stock_Quantity = :stock_quantity, 
                Lowstock_Threshold = :lowstock_threshold, 
                Supplier_ID = :supplier_id,
                Category_ID = :category_id
            WHERE Product_ID = :id
        ");

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
        $stmt = $this->db->prepare("DELETE FROM powerhuff_db.PRODUCTS WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getLowStockProducts()
    {
        $stmt = $this->db->query("SELECT * FROM products WHERE Stock_Quantity <= Lowstock_Threshold");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
}