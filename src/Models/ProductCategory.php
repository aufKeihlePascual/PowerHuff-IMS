<?php

namespace App\Models;

class ProductCategory extends BaseModel
{
    
    public function getAllProductCategories()
    {
        $stmt = $this->db->query("SELECT name, description FROM product_category");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}