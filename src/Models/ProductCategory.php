<?php

namespace App\Models;

class ProductCategory extends BaseModel
{
    
    public function getAllProductCategories()
    {
        $stmt = $this->db->query("SELECT Product_ID, Category_ID, Name AS product_category_name, Description FROM product_category");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM product_category WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function totalCountProductCategories() {
        $result = $this->db->query("SELECT COUNT(DISTINCT category_id) AS total FROM product_category");
        return $result->fetchColumn();
    }

    public function addProductCategory($category_name, $category_description)
    {
        $query = "INSERT INTO product_category (Name, Description) VALUES (:category_name, :category_description)";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_description', $category_description);
        
        return $stmt->execute();
    }

    public function updateProductCategory($id, $name, $description)
    {
        $query = "UPDATE product_category 
                SET Name = :name, Description = :description 
                WHERE Category_ID = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function insertProductCategory($name, $description)
    {
        $query = "INSERT INTO product_category (Name, Description) 
                VALUES (:name, :description)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }
}