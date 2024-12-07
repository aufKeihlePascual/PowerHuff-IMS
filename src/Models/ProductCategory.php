<?php

namespace App\Models;

class ProductCategory extends BaseModel
{
    
    public function getAllProductCategories()
    {
        $stmt = $this->db->query("SELECT Product_ID, Category_ID, Name, Description FROM product_category");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addProductCategory($category_name, $category_description)
    {
        // Insert into the powerhuff_db, which includes Product_ID, Category_ID, Name, and Description
        $query = "INSERT INTO product_category (Name, Description) VALUES (:category_name, :category_description)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_description', $category_description);
        
        return $stmt->execute();
    }
}