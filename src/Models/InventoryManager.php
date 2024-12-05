<?php

namespace App\Models;

class InventoryManager extends BaseModel
{
    public function getAllProducts()
    {
        global $conn;

        $stmt = $conn->query("SELECT * FROM powerhuff_db.PRODUCTS");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id)
    {
        global $conn;

        $stmt = $conn->prepare("INSERT INTO powerhuff_db.PRODUCTS (Product_Name, Price, Stock_Quantity, Lowstock_Threshold, Supplier_ID, Created_On)
                                VALUES (:product_name, :price, :stock_quantity, :lowstock_threshold, :supplier_id, CURRENT_TIMESTAMP)");
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);

        return $stmt->execute();
    }

    public function updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE powerhuff_db.PRODUCTS 
                                SET Product_Name = :product_name, 
                                    Price = :price, 
                                    Stock_Quantity = :stock_quantity, 
                                    Lowstock_Threshold = :lowstock_threshold, 
                                    Supplier_ID = :supplier_id 
                                WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        global $conn;

        $stmt = $conn->prepare("DELETE FROM powerhuff_db.PRODUCTS WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }
}

