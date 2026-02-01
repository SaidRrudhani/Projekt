<?php

class User{
    private $conn;
    private $table_name = "user";

    public function __construct($db){
        $this->conn = $db;
    }

    public function register($Fullname, $Email, $Password){
        try {
            $query = "INSERT INTO {$this->table_name} (Fullname, Email, Password, Role) VALUES (:Fullname, :Email, :Password, :Role)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':Fullname', $Fullname);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', password_hash($Password, PASSWORD_BCRYPT));
            
            // Default Role to 0 (User)
            $role = 0;
            $stmt->bindParam(':Role', $role);

            if($stmt->execute()){
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($Email, $Password){
        $query = "SELECT id, Fullname, Email, Password, Role FROM {$this->table_name} WHERE Email = :Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($Password, $row['Password'])){
                if (session_status() == PHP_SESSION_NONE) session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['Email'] = $row['Email'];
                $_SESSION['Role'] = $row['Role'];
                return true;
            }
            return "incorrect_password";
        }
        return "email_not_found";
    }

    public function getAllUsers() {
        $query = "SELECT id, Fullname, Email, Role, created_at FROM {$this->table_name} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id AND Role != 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function kickUser($id) {
        $query = "UPDATE {$this->table_name} SET force_logout = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function isKicked($id) {
        try {
            $query = "SELECT force_logout FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($user && $user['force_logout'] == 1);
        } catch (PDOException $e) {
            return false; // Column might not exist yet
        }
    }
}

?>