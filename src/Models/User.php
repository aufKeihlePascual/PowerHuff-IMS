<?php

namespace App\Models;

class User extends BaseModel
{
    public $username, $password, $password_hash;
    protected $table = 'users';

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
        $stmt = $this->db->query("SELECT user_id, first_name, last_name, username, REPLACE(role, '_', ' ') AS role FROM users");
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

}
