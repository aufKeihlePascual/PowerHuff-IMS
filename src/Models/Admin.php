<?php

namespace App\Models;

class Admin extends User
{
    public function createUser($first_name, $last_name, $username, $password, $role)
    {
        global $conn;

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, password_hash, role) VALUES (:first_name, :last_name, :username, :password_hash, :role)");
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password_hash', $password_hash);
        $stmt->bindParam(':role', $role);

        return $stmt->execute();
    }

    public function EditUser($userId, $firstName, $lastName, $username, $role, $password = null)
    {
        global $conn;

        $userId = (int) $userId;

        $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists == 0) {
            return false;
        }

        $sql = "UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, role = :role";

        if ($password !== null) {
            $sql .= ", password = :password";
        }

        $sql .= " WHERE user_id = :user_id"; 

        $stmt = $conn->prepare($sql);

        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':user_id', $userId, \PDO::PARAM_INT);

        if ($password !== null) {
            $stmt->bindParam(':password', $password);
        }

        return $stmt->execute();
    }

    public function updateUser($user_id, $data)
    {
        global $conn;

        $setClause = [];
        $values = [];

        foreach ($data as $key => $value) {
            $setClause[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $user_id;

        $sql = "UPDATE users SET " . implode(', ', $setClause) . " WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($values);
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

}
