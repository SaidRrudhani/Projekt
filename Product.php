<?php

class Product {
    private $conn;
    private $table_name = "product";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM {$this->table_name} ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table_name} WHERE ProductID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($ProductName, $description, $image_path, $Quantity) {
        $query = "INSERT INTO {$this->table_name} (ProductName, description, image_path, Quantity, Nr) VALUES (:name, :desc, :img, :qty, 0)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $ProductName);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':img', $image_path);
        $stmt->bindParam(':qty', $Quantity);
        return $stmt->execute();
    }

    public function update($id, $ProductName, $description, $Quantity, $image_path = null) {
        $imgSql = $image_path ? ", image_path = :img" : "";
        $query = "UPDATE {$this->table_name} SET ProductName = :name, description = :desc, Quantity = :qty $imgSql WHERE ProductID = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $ProductName);
        $stmt->bindParam(':desc', $description);
        $stmt->bindParam(':qty', $Quantity);
        if ($image_path) {
            $stmt->bindParam(':img', $image_path);
        }
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table_name} WHERE ProductID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getCount() {
        $query = "SELECT COUNT(*) FROM {$this->table_name}";
        return $this->conn->query($query)->fetchColumn();
    }
}
?>
