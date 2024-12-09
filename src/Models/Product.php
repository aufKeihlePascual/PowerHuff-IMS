<?php

namespace App\Models;

class Product extends BaseModel
{
    public function getAllProducts()
    {
        $stmt = $this->db->query("
            SELECT 
                p.product_id AS Product_ID,
                p.product_name AS Product_Name,
                p.description AS Product_Description,
                p.price AS Price,
                p.stock_quantity AS Stock_Quantity,
                p.lowstock_threshold AS Lowstock_Threshold,
                CONCAT(UPPER(SUBSTRING(s.supplier_name, 1, 1)), LOWER(SUBSTRING(s.supplier_name, 2))) AS Supplier_Name, 
                p.created_on AS Created_On,
                c.name AS Category_Name
            FROM 
                products p
            LEFT JOIN 
                product_category pc ON p.product_id = pc.product_id  -- join with product_category
            LEFT JOIN 
                categories c ON pc.category_id = c.category_id  -- join with categories through product_category
            LEFT JOIN 
                suppliers s ON p.supplier_id = s.supplier_id
            ORDER BY 
                p.product_id DESC;

        ");

        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['Supplier_Name'] = ucwords(strtolower($product['Supplier_Name']));
        }

        return $products;
    }

    public function totalCountProducts() {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM products");
        return $result->fetchColumn();
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
                pc.Category_ID,
                c.Name AS Category_Name,
                pc.Name AS Product_Category_Name
            FROM 
                PRODUCTS p
            LEFT JOIN 
                PRODUCT_CATEGORY pc ON p.Product_ID = pc.Product_ID
            LEFT JOIN 
                CATEGORIES c ON pc.Category_ID = c.Category_ID
            LEFT JOIN
                SUPPLIERS s ON p.Supplier_ID = s.Supplier_ID
            WHERE
                p.Product_ID = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addProduct($product_name, $description, $price, $stock_quantity, $lowstock_threshold, $supplier_id)
    {
        $stmt = $this->db->prepare("
            INSERT INTO products (product_name, description, price, stock_quantity, lowstock_threshold, supplier_id, created_on)
            VALUES (:product_name, :description, :price, :stock_quantity, :lowstock_threshold, :supplier_id, CURRENT_TIMESTAMP)
        ");

        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);

        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function mapProductToCategory($product_id, $category_id)
    {
        $stmt = $this->db->prepare("
            INSERT INTO product_category (product_id, category_id)
            VALUES (:product_id, :category_id)
        ");

        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        $stmt = $this->db->prepare("
            UPDATE products 
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
        $stmt = $this->db->prepare("DELETE FROM PRODUCTS WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getLowStockProducts()
    {
        $stmt = $this->db->query("SELECT * FROM products WHERE Stock_Quantity <= Lowstock_Threshold");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductCountsByCategory() {
        
        $stmt = $this->db->prepare("
            SELECT c.name AS category_name, COUNT(p.product_id) AS total_products
            FROM categories c
            LEFT JOIN product_category pc ON c.category_id = pc.category_id
            LEFT JOIN products p ON pc.product_id = p.product_id
            GROUP BY c.category_id;
        ");

        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalProductItemsPerCategory() {
        $result = $this->db->query("
            SELECT c.name AS category_name, COUNT(pi.product_item_id) AS total_product_items
            FROM categories c
            LEFT JOIN product_category pc ON c.category_id = pc.category_id
            LEFT JOIN products p ON pc.product_id = p.product_id
            LEFT JOIN product_items pi ON p.product_id = pi.product_id
            GROUP BY c.category_id
        ");
        
        $categories = [];
        
        while ($row = $result->fetch(\PDO::FETCH_ASSOC)) {
            $categories[] = $row;
        }
        
        return $categories;
    }
    
    
}