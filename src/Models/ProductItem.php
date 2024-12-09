<?php

namespace App\Models;

class ProductItem extends BaseModel
{
    public function getAllProductItems()
    {
        $stmt = $this->db->query("
            SELECT p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On, pi.Updated_On
            FROM product_items pi
            JOIN products p
            ON pi.Product_ID = p.Product_ID
        ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function totalCountProductItems() {
        $result = $this->db->query("SELECT COUNT(*) AS total FROM product_items");
        return $result->fetchColumn();
    }
    
    public function getProductItemById($id)
    {
        $stmt = $this->db->prepare("
            SELECT pi.Item_ID, pi.Product_ID, p.Product_Name, pi.Price, pi.Stock_Quantity, pi.Created_On 
            FROM product_items pi
            JOIN products p ON pi.Product_ID = p.Product_ID
            WHERE pi.Item_ID = :id
        ");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function addProductItem($product_id, $price, $stock_quantity)
    {
        $stmt = $this->db->prepare("
            INSERT INTO powerhuff_db.PRODUCT_ITEMS (Product_ID, Price, Stock_Quantity, Created_On)
            VALUES (:product_id, :price, :stock_quantity, CURRENT_TIMESTAMP)
        ");
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

        return $stmt->execute();
    }

    public function updateProductItem($id, $product_id, $price, $stock_quantity)
    {
        $stmt = $this->db->prepare("
            UPDATE powerhuff_db.PRODUCT_ITEMS 
            SET Product_ID = :product_id, 
                Price = :price, 
                Stock_Quantity = :stock_quantity
            WHERE Item_ID = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

        return $stmt->execute();
    }
    
}