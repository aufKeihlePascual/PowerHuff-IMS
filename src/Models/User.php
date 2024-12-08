<?php

namespace App\Models;

class User extends BaseModel
{
    public $username, $password, $password_hash;

    public function login($username, $password)
    {
        $userData = $this->findByUsername($username);

        if ($userData && password_verify($password, $userData['password_hash'])) {
            return $userData;
        }
        return false;
    }

    public function findByUserID($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function findByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(); 
    }

    public function getAllUsers()
    {
        $stmt = $this->db->query("
            SELECT 
                u.user_id, 
                CONCAT(first_name, ' ', last_name) AS full_name, 
                u.username, 
                u.role, 
                DATE_FORMAT(u.created_on, '%b %d, %Y %h:%i %p') AS created_on, 
                DATE_FORMAT(u.updated_on, '%b %d, %Y %h:%i %p') AS updated_on,
                (SELECT logged_time 
                FROM login_activity 
                WHERE user_id = u.user_id AND activity_type = 'login' 
                ORDER BY logged_time DESC LIMIT 1) AS last_login,
                (SELECT logged_time 
                FROM login_activity 
                WHERE user_id = u.user_id AND activity_type = 'logout' 
                ORDER BY logged_time DESC LIMIT 1) AS last_logout
            FROM users u
        ");
        $stmt->execute();
        
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }

    public function logActivity($userId, $activityType)
    {
        $stmt = $this->db->prepare("
            INSERT INTO login_activity (user_id, activity_type, logged_time) 
            VALUES (:user_id, :activity_type, NOW())
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':activity_type' => $activityType,
        ]);
    }


}
