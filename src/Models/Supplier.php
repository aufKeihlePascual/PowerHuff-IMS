<?php

namespace App\Models;

class Supplier extends User
{
    public function createSupplier($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO suppliers (supplier_name, product_categoryID, contact_number, email, address, created_on) 
            VALUES (:supplier_name, :product_categoryID, :contact_number, :email, :address, :created_on)"
        );

        $stmt->bindParam(':supplier_name', $data['supplier_name']);
        $stmt->bindParam(':product_categoryID', $data['product_categoryID']);
        $stmt->bindParam(':contact_number', $data['contact_number']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':address', $data['address']);
        $stmt->bindParam(':created_on', $data['created_on']);

        return $stmt->execute();
    }

    public function getAllSuppliers()
    {
        $stmt = $this->db->query("SELECT * FROM suppliers");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function updateSupplier($supplier_id, $data)
    {
        $sql = "UPDATE suppliers SET supplier_name = ?, product_categoryID = ?, contact_number = ?, email = ?, address = ? WHERE supplier_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['supplier_name'],
            $data['product_categoryID'],
            $data['contact_number'],
            $data['email'],
            $data['address'],
            $supplier_id
        ]);
        
        return $stmt->rowCount() > 0;
    }

    public function deleteSupplier($supplierId)
    {
        $stmt = $this->db->prepare("DELETE FROM suppliers WHERE supplier_id = :supplier_id");
        $stmt->bindParam(':supplier_id', $supplierId);

        return $stmt->execute();
    }


    public function findBySupplierID($supplier_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE supplier_id = ?");
        $stmt->execute([$supplier_id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    private function findBySupplierName($supplier_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE supplier_name = ?");
        $stmt->execute([$supplier_name]);
        return $stmt->fetch(); 
    }


    public function getSupplierBySupplierName($supplier_name)
    {
        $stmt = $this->db->prepare("SELECT * FROM suppliers WHERE supplier_name = :supplier_name");
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC); 
    }
}
