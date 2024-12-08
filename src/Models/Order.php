<?php

namespace App\Models;

class Order extends BaseModel
{
    public function getAllOrders()
    {
        $stmt = $this->db->query("
            SELECT 
                o.order_id,
                o.order_name,
                o.description,
                o.order_date,
                o.status, 
                s.supplier_name, 
                CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                o.updated_on
            FROM 
                orders o
            JOIN 
                suppliers s ON o.supplier_id = s.supplier_id
            JOIN 
                users u ON o.user_id = u.user_id
            ORDER BY o.order_date DESC;
        ");
        
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['supplier_name'] = ucwords(strtolower($order['supplier_name']));

            $order['formatted_order_date'] = date('M d, Y h:i A', strtotime($order['order_date']));
        }

        return $orders;
    }

    public function getOrderById($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                o.order_id, o.order_name, o.description, o.order_date, o.status,
                s.supplier_name, CONCAT(u.first_name, ' ', u.last_name) AS user_name,
                o.updated_on
            FROM 
                orders o
            JOIN 
                suppliers s ON o.supplier_id = s.supplier_id
            JOIN 
                users u ON o.user_id = u.user_id
            WHERE 
                o.order_id = :order_id;
        ");
        
        $stmt->bindParam(':order_id', $orderId, \PDO::PARAM_INT);
        $stmt->execute();
        
        $order = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($order) {
            $order['supplier_name'] = ucwords(strtolower($order['supplier_name']));
            $order['formatted_order_date'] = date('M d, Y h:i A', strtotime($order['order_date']));
        }

        return $order;
    }

    public function getOrderItems($orderId)
    {
        $stmt = $this->db->prepare("
            SELECT 
                oi.order_itemID, 
                oi.order_id, 
                oi.quantity, 
                oi.price, 
                p.product_name, 
                p.description AS product_description 
            FROM 
                order_item oi
            JOIN 
                products p ON oi.product_id = p.product_id
            WHERE 
                oi.order_id = :order_id
        ");
        
        $stmt->bindParam(':order_id', $orderId, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAllSuppliers()
    {
        $stmt = $this->db->query("
            SELECT DISTINCT 
                s.supplier_id, 
                s.supplier_name
            FROM 
                suppliers s
        ");
        
        $suppliers = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach ($suppliers as &$supplier) {
            $supplier['supplier_name'] = ucwords(strtolower($supplier['supplier_name']));
        }

        return $suppliers;
    }


    public function createOrder($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO orders (order_name, description, order_date, status, supplier_id, user_id)
            VALUES (:order_name, :description, :order_date, :status, :supplier_id, :user_id)
        ");
        
        $stmt->bindParam(':order_name', $data['order_name']);
        
        if ($data['description'] === null) {
            $stmt->bindValue(':description', null, \PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':description', $data['description']);
        }

        $stmt->bindParam(':order_date', $data['order_date']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':supplier_id', $data['supplier_id']);
        $stmt->bindParam(':user_id', $data['user_id']);
        
        return $stmt->execute();
    }

    public function updateOrder($orderId, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE orders
            SET order_name = :order_name, description = :description, order_date = :order_date, status = :status, supplier_id = :supplier_id
            WHERE order_id = :order_id
        ");

        $stmt->bindParam(':order_name', $data['order_name']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':order_date', $data['order_date']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':supplier_id', $data['supplier_id']);
        $stmt->bindParam(':order_id', $orderId);

        return $stmt->execute();
    }

    public function deleteOrder($orderId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM orders WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $orderId);
        $stmt->execute();
        $orderExists = $stmt->fetchColumn();

        if ($orderExists == 0) {
            return false;
        }
        
        $stmt = $this->db->prepare("DELETE FROM orders WHERE order_id = :order_id");
        $stmt->bindParam(':order_id', $orderId);

        return $stmt->execute();
    }

}