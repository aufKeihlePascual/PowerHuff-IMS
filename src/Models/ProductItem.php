<?php

namespace App\Models;

class Product extends BaseModel
{
    public function getAllProductItems()
    {
        $stmt = $this->db->query("
            SELECT p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On, pi.Updated_On
            FROM product_items pi
            JOIN products p ON pi.Product_ID = p.Product_ID
        ");
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
    
}