<?php

namespace App\Models;

class Category extends BaseModel
{

    public function getAllCategories()
    {
        $stmt = $this->db->query("SELECT name, description, DATE_FORMAT(created_on, '%b %d, %Y %h:%i %p') AS created_on, DATE_FORMAT(updated_on, '%b %d, %Y %h:%i %p') AS updated_on FROM categories");
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT Category_ID, Name, Description, Created_On FROM categories WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllProductCategories()
    {
        $stmt = $this->db->query("SELECT name, description FROM product_category");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}