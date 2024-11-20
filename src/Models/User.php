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
        return false; // 
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

        $stmt = $conn->query("SELECT id, first_name, last_name, email FROM users");
        return $stmt->fetchAll();
    }
}