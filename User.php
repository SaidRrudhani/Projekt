<?php

class User {
    private $conn;
    private $table_name = "user";

    /**
     * @param PDO $db
     */
    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /**
     * @param string $Fullname
     * @param string $Email
     * @param string $Password
     * @return bool
     */
    public function register(string $Fullname, string $Email, string $Password): bool {
        try {
            $query = "INSERT INTO {$this->table_name} (Fullname, Email, Password, Role) VALUES (:Fullname, :Email, :Password, :Role)";
            $stmt = $this->conn->prepare($query);

            $stmt->bindParam(':Fullname', $Fullname);
            $stmt->bindParam(':Email', $Email);
            $stmt->bindParam(':Password', password_hash($Password, PASSWORD_BCRYPT));
            
            $role = 0;
            $stmt->bindParam(':Role', $role);

            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * @param string $Email
     * @param string $Password
     * @return bool|string
     */
    public function login(string $Email, string $Password): bool|string {
        $query = "SELECT id, Fullname, Email, Password, Role FROM {$this->table_name} WHERE Email = :Email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':Email', $Email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($Password, $row['Password'])) {
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

    /**
     * @return array
     */
    public function getAllUsers(): array {
        $query = "SELECT id, Fullname, Email, Role, created_at FROM {$this->table_name} ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function deleteUser(int|string $id): bool {
        $query = "DELETE FROM {$this->table_name} WHERE id = :id AND Role != 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function kickUser(int|string $id): bool {
        $query = "UPDATE {$this->table_name} SET force_logout = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * @param int|string $id
     * @return bool
     */
    public function isKicked(int|string $id): bool {
        try {
            $query = "SELECT force_logout FROM {$this->table_name} WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($user && $user['force_logout'] == 1);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>