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

    public function EditUser($userId, $firstName, $lastName, $username, $role, $password = null)
    {
        global $conn;

        // Ensure user_id is treated as an integer
        $userId = (int) $userId;

        // Check if the user exists
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists == 0) {
            return false;
        }

        // Update SQL query
        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, role = :role";

        // Add password update if provided
        if ($password !== null) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE user_id = :user_id"; // This ensures you're updating the correct user based on user_id

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        // Bind password if provided
        if ($password !== null) {
            $stmt->bindParam(':password', $password);
        }

        // Execute the query
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
