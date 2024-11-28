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

    private function findByUsername($username)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(); 
    }

    public function getAllUsers()
    {
        global $conn;

        $stmt = $conn->query("SELECT user_id, first_name, last_name, username, REPLACE(role, '_', ' ') AS role FROM users");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $roleStyles = [
            'admin' => 'role-admin',
            'inventory-manager' => 'role-inventory-manager',
            'procurement-manager' => 'role-procurement-manager'
        ];
    }

    public function getUserByUsername($username)
    {
        global $conn;
        
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }
}
