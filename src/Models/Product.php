<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = 'products';

    public function getAllProducts()
    {
        $stmt = $this->db->query("SELECT * FROM products");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createProduct($data)
    {
        $sql = "INSERT INTO products (product_name, description, price, stock_quantity, lowstock_threshold, supplier_id, created_on) 
                VALUES (:product_name, :description, :price, :stock_quantity, :lowstock_threshold, :supplier_id, NOW())";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    public function updateProduct($id, $data)
    {
        $data['updated_on'] = date('Y-m-d H:i:s');
        $setClause = [];
        $values = [];

        foreach ($data as $key => $value) {
            $setClause[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $id;
        $sql = "UPDATE products SET " . implode(', ', $setClause) . " WHERE product_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function deleteProduct($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE product_id = ?");
        return $stmt->execute([$id]);
    }
}
