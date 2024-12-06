<?php

namespace App\Models;

class InventoryManager extends BaseModel
{

    public function addProduct($product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        $stmt = $this->db->prepare("
            INSERT INTO products (Product_Name, Price, Stock_Quantity, Lowstock_Threshold, Supplier_ID, Category_ID, Created_On)
            VALUES (:product_name, :price, :stock_quantity, :lowstock_threshold, :supplier_id, :category_id, CURRENT_TIMESTAMP)
        ");

        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function updateProduct($id, $product_name, $price, $stock_quantity, $lowstock_threshold, $supplier_id, $category_id)
    {
        $stmt = $this->db->prepare("
            UPDATE products
            SET Product_Name = :product_name, 
                Price = :price, 
                Stock_Quantity = :stock_quantity, 
                Lowstock_Threshold = :lowstock_threshold, 
                Supplier_ID = :supplier_id,
                Category_ID = :category_id
            WHERE Product_ID = :id
        ");

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':product_name', $product_name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_quantity', $stock_quantity);
        $stmt->bindParam(':lowstock_threshold', $lowstock_threshold);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':category_id', $category_id);

        return $stmt->execute();
    }

    public function deleteProduct($id)
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE Product_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
    }

    public function addCategory($category_name, $description)
    {
        $stmt = $this->db->prepare("
            INSERT INTO categories (Category_Name, Description, Created_On)
            VALUES (:category_name, :description, CURRENT_TIMESTAMP)
        ");
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':description', $description);

        return $stmt->execute();
    }

    public function updateCategory($id, $category_name, $description)
    {
        $stmt = $this->db->prepare("
            UPDATE categories
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
        $stmt = $this->db->prepare("DELETE FROM categories WHERE Category_ID = :id");
        $stmt->bindParam(':id', $id);

        return $stmt->execute();
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