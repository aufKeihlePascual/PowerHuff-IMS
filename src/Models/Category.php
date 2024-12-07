<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new \App\Models\Category();
    }

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

    public function addCategory($category_name, $description)
    {
        $stmt = $this->db->prepare("
            INSERT INTO powerhuff_db.CATEGORIES (Category_Name, Description, Created_On)
            VALUES (:category_name, :description, CURRENT_TIMESTAMP)
        ");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function updateCategory($id, $category_name, $description)
    {
        $stmt = $this->db->prepare("
            UPDATE powerhuff_db.CATEGORIES 
            SET Category_Name = :category_name, 
                Description = :description
            WHERE Category_ID = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function deleteCategory($id)
    {
        $result = $this->categoryModel->deleteCategory($id);


        if ($result) {
            $_SESSION['success_message'] = "Category deleted successfully.";
        } else {
            $_SESSION['error_message'] = "Failed to delete category.";
        }

        header('Location: /dashboard/categories');
        exit;
    }

}