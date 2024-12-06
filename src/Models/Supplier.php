<?php

namespace App\Models;

class Supplier extends User
{
    public function createSupplier($data)
    {
        $stmt = $this->db->prepare(
                "INSERT INTO suppliers (supplier_name, product_categoryID, contact_number, email, address, created_on) 
                VALUES (:supplier_name, :product_categoryID, :contact_number, :password_hash, :role, :created_on)"
            );

        $stmt->bindParam(':first_name', $data['first_name']);
        $stmt->bindParam(':last_name', $data['last_name']);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':password_hash', $data['password_hash']);
        $stmt->bindParam(':role', $data['role']);
        $stmt->bindParam(':created_on', $data['created_on']);

        return $stmt->execute();
    }

    public function updateUser($user_id, $data)
    {
        $setClause = [];
        $values = [];

        foreach ($data as $key => $value) {
            $setClause[] = "$key = ?";
            $values[] = $value;
        }

        $values[] = $user_id;

        $sql = "UPDATE users SET " . implode(', ', $setClause) . " WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    public function deleteUser($userId)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $userExists = $stmt->fetchColumn();

        if ($userExists == 0) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();
    }

    public function getAllUsers()
    {
        $stmt = $this->db->query("SELECT user_id, username, role FROM users");
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
