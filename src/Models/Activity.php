<?php

namespace App\Models;

class Activity extends User
{
    public function getUserActivities($filters = [])
    {
        
        $validFilters = [
            'user_id' => 'u.user_id',
            'activity_type' => 'pa.activity_type',
            'role' => 'u.role'
        ];

        $sql = "
            SELECT 
                u.username, 
                u.first_name, 
                u.last_name, 
                pa.activity_type AS product_activity_type, 
                DATE_FORMAT(pa.logged_on, '%b %d, %Y %I:%i:%s %p') AS product_logged_on,
                sa.change_type AS stock_change_type,
                sa.change_quantity AS stock_change_quantity,
                DATE_FORMAT(sa.logged_on, '%b %d, %Y %I:%i:%s %p') AS stock_logged_on,
                p.product_name,
                c.name AS category_name
            FROM 
                users u
            LEFT JOIN 
                product_activity pa ON u.user_id = pa.user_id
            LEFT JOIN 
                stock_activity sa ON pa.product_activityID = sa.product_activityID
            LEFT JOIN
                products p ON pa.product_id = p.product_id
            LEFT JOIN
                categories c ON pa.category_id = c.category_id
            WHERE
                (sa.change_quantity IS NOT NULL AND sa.change_quantity != '')
        ";

        $whereClause = [];
        foreach ($filters as $key => $value) {
            if (isset($validFilters[$key]) && !empty($value)) {
                $whereClause[] = $validFilters[$key] . ' = :' . $key;
            }
        }

        if ($whereClause) {
            $sql .= " AND " . implode(" AND ", $whereClause); 
        }

        $stmt = $this->db->prepare($sql);

        foreach ($filters as $key => $value) {
            if (isset($validFilters[$key]) && !empty($value)) {
                $stmt->bindParam(':' . $key, $value);
            }
        }

        $stmt->execute();
        $activities = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $filteredActivities = array_filter($activities, function ($activity) {
            return !empty($activity['product_activity_type']) || !empty($activity['stock_change_type']);
        });

        return $filteredActivities;
    }
}