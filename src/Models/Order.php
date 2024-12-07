<?php

namespace App\Models;

class Order extends BaseModel
{
    public function getAllOrders()
    {
        $stmt = $this->db->query("
            SELECT 
            o.order_id, 
            DATE_FORMAT(o.order_date, '%b %d, %Y %h:%i %p') AS order_date,
            o.status, 
            s.supplier_name, 
            CONCAT(u.first_name, ' ', u.last_name) AS user_name 
        FROM 
            orders o
        JOIN 
            suppliers s ON o.supplier_id = s.supplier_id
        JOIN 
            users u ON o.user_id = u.user_id;
        ");
        
        $orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($orders as &$order) {
            $order['supplier_name'] = ucwords(strtolower($order['supplier_name']));
        }

        return $orders;
    }
    
}