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
                powerhuff_db.PRODUCTS p
            LEFT JOIN 
                powerhuff_db.PRODUCT_CATEGORY pc ON p.Product_ID = pc.Product_ID
            LEFT JOIN 
                powerhuff_db.CATEGORIES c ON pc.Category_ID = c.Category_ID
            LEFT JOIN
                powerhuff_db.SUPPLIERS s ON p.Supplier_ID = s.Supplier_ID
        ");

        $products = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($products as &$product) {
            $product['Supplier_Name'] = ucwords(strtolower($product['Supplier_Name']));
        }

        return $products;
    }

    public function getAllCategories()
    {
        $stmt = $this->db->query("SELECT Category_ID, Name, Created_On FROM powerhuff_db.CATEGORIES");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllProductItems()
    {
        $stmt = $this->db->query("
            SELECT p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On, pi.Updated_On
            FROM powerhuff_db.PRODUCT_ITEMS pi
            JOIN powerhuff_db.PRODUCTS p ON pi.Product_ID = p.Product_ID
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getProductsByCategory($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getLowStockProducts()
    {
        $stmt = $this->db->query("SELECT * FROM products WHERE Stock_Quantity <= Lowstock_Threshold");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT Category_ID, Name, Description, Created_On FROM powerhuff_db.CATEGORIES WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getProductItemById($id)
    {
        $stmt = $this->db->prepare("
            SELECT pi.Item_ID, pi.Product_ID, p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On 
            FROM powerhuff_db.PRODUCT_ITEMS pi
            JOIN powerhuff_db.PRODUCTS p ON pi.Product_ID = p.Product_ID
            WHERE pi.Item_ID = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}