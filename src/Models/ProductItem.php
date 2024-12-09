<?php

namespace App\Models;

class ProductItem extends BaseModel
{
    public function getAllProductItems()
    {
        $stmt = $this->db->query("
            SELECT pi.product_item_id, p.Product_Name, pi.size, pi.color, pi.price, pi.stock_quantity, pi.created_on, pi.updated_on
            FROM product_items pi
            JOIN products p ON pi.product_id = p.Product_ID
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getProductItemById($id)
    {
        $stmt = $this->db->prepare("
            SELECT pi.product_item_id, pi.product_id, p.Product_Name, pi.size, pi.color, pi.price, pi.stock_quantity, pi.created_on, pi.updated_on
            FROM product_items pi
            JOIN products p ON pi.product_id = p.Product_ID
            WHERE pi.product_item_id = :id
        ");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addProductItem($product_id, $size, $color, $price, $stock_quantity)
    {
        $stmt = $this->db->prepare("
            INSERT INTO product_items (product_id, size, color, price, stock_quantity)
            VALUES (:product_id, :size, :color, :price, :stock_quantity)
        ");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

        return $stmt->execute();
    }

    public function updateProductItem($id, $product_id, $size, $color, $price, $stock_quantity)
    {
        $stmt = $this->db->prepare("
            UPDATE product_items 
            SET product_id = :product_id, 
                size = :size,
                color = :color,
                price = :price, 
                stock_quantity = :stock_quantity,
                updated_on = CURRENT_TIMESTAMP
            WHERE product_item_id = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':color', $color);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

        return $stmt->execute();
    }
}