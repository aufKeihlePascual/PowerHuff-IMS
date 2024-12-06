<?php

namespace App\Models;

class Category extends BaseModel
{

    public function getAllCategories()
    {
        $stmt = $this->db->query("SELECT Category_ID, Name, Created_On FROM powerhuff_db.CATEGORIES");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductsByCategory($category_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM powerhuff_db.PRODUCTS WHERE Category_ID = :category_id");
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT Category_ID, Name, Description, Created_On FROM powerhuff_db.CATEGORIES WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

}