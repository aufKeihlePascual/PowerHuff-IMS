<?php

namespace App\Models;

class BaseModel
{
    protected $db;

    public function __construct()
    {
        global $conn;

        if (!$conn) {
            throw new \Exception('Database connection not established.');
        }

        $this->db = $conn;        
    }

    public function fill($payload)
    {
        foreach ($payload as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function find($table, $id)
    {
        $sql = "SELECT * FROM $table WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
