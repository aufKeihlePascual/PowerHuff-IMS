<?php

namespace App\Models;

class Category extends BaseModel
{
    public function getAllCategories()
    {
        $stmt = $this->db->query("
            SELECT category_id, name, description, 
            DATE_FORMAT(created_on, '%b %d, %Y %h:%i %p') AS created_on, 
            DATE_FORMAT(updated_on, '%b %d, %Y %h:%i %p') AS updated_on 
            FROM categories
        ");
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function totalCountCategories()
    {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM categories");
        return $result->fetchColumn();
    }

    public function addCategory($category_name, $description)
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, description, created_on, updated_on) 
            VALUES (:category_name, :description, CURRENT_TIMESTAMP, NULL)
        ");

        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function deleteCategory($id)
    {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE category_id = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE category_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function updateCategory($id, $name, $description)
    {
        $stmt = $this->db->prepare(
            "UPDATE categories 
            SET name = :name, description = :description, updated_on = CURRENT_TIMESTAMP 
            WHERE category_id = :id"
        );
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }
}
