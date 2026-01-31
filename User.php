<?php

class User{
    private $conn;
    private $table_name = "user";

    public function __construct($db){
        $this->conn = $db;
    }

    public function register($Fullname, $Email, $Password){
        $query = "INSERT INTO {$this->table_name} {Fullname, Email, Password} VALUES (:Fullname, :Email, :Password)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':Fullname', $Fullname);
        $stmt->bindParam(':Email', $Email);
        $stmt->bindParam(':Password', Password_hash($Password, PASSWORD_BCRYPT));

        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function login($Email, $Password){
        $query = "SELECT id,Fullname, Email, Password FROM {$this->table_name} WHERE Email = :Email";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':Email', $Email);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            $row=$stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($Password, $row['Password'])){
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['Email'] = $row['Email'];
                return true;
            }
        }
        return false;
    }
    
}

?>