<?php

namespace App\Models;

class Admin extends BaseModel
{
    public function createUser($first_name, $last_name, $username, $password, $role)
    {
        global $conn;

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password_hash, role) VALUES (:first_name, :last_name, :username, :password_hash, :role)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $passwordHash);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    public function updateUser($userId, $username, $role)
    {
        global $conn;

        $stmt = $conn->prepare("UPDATE users SET username = :username, role = :role WHERE user_id = :user_id");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }

    public function deleteUser($userId)
    {
        global $conn;

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists == 0) {
            return false;
        }

        $stmt = $conn->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }

    public function getAllUsers()
    {
        global $conn;

        $stmt = $conn->query("SELECT user_id, username, role FROM users");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // public function getAllUsers()
    // {
    //     global $conn;

    //     $stmt = $conn->query("SELECT user_id, first_name, last_name, username, REPLACE(role, '_', ' ') AS role FROM users");
    //     $stmt->execute();
    //     return $stmt->fetchAll(\PDO::FETCH_ASSOC);

    //     $roleStyles = [
    //         'admin' => 'role-admin',
    //         'inventory-manager' => 'role-inventory-manager',
    //         'procurement-manager' => 'role-procurement-manager'
    //     ];
    // }
}
