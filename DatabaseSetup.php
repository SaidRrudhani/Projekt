<?php
include_once 'Database.php';

class DatabaseSetup {
    private $conn;
    private $host = 'localhost';
    private $username = 'root';
    private $password = '';
    private $db_name = 'projectbase';

    public function __construct() {
        try {
            $pdo = new PDO("mysql:host={$this->host}", $this->username, $this->password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS {$this->db_name}");
            
            $db = new Database();
            $this->conn = $db->getConnection();
        } catch (PDOException $e) {
            die("Setup Connection Error: " . $e->getMessage());
        }
    }

    public function initTables() {
        $this->createUserTable();
        $this->createProductTable();
        $this->updateSchema();
    }

    private function createUserTable() {
        $sql = "CREATE TABLE IF NOT EXISTS user (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Fullname VARCHAR(100) NOT NULL,
            Email VARCHAR(100) NOT NULL UNIQUE,
            Password VARCHAR(255) NOT NULL,
            Role INT DEFAULT 0,
            force_logout TINYINT(1) DEFAULT 0,
            last_seen TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->conn->exec($sql);
    }

    private function createProductTable() {
        $sql = "CREATE TABLE IF NOT EXISTS product (
            ProductID INT AUTO_INCREMENT PRIMARY KEY,
            ProductName VARCHAR(255) NOT NULL,
            description TEXT,
            image_path VARCHAR(255),
            Quantity INT DEFAULT 0,
            Nr INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $this->conn->exec($sql);
    }

    public function updateSchema() {
        $this->ensureColumn('user', 'force_logout', 'TINYINT(1) DEFAULT 0');
        $this->ensureColumn('user', 'last_seen', 'TIMESTAMP NULL DEFAULT NULL');

        $this->ensureColumn('product', 'description', 'TEXT');
        $this->ensureColumn('product', 'image_path', 'VARCHAR(255)');
        $this->ensureColumn('product', 'created_at', 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    }

    private function ensureColumn($table, $column, $definition) {
        $stmt = $this->conn->prepare("SHOW COLUMNS FROM `$table` LIKE :column");
        $stmt->execute([':column' => $column]);
        if ($stmt->rowCount() == 0) {
            $this->conn->exec("ALTER TABLE `$table` ADD COLUMN `$column` $definition");
        }
    }

    public function seedData() {
        $checkProd = $this->conn->query("SELECT count(*) FROM product")->fetchColumn();
        if ($checkProd == 0) {
            $stmt = $this->conn->prepare("INSERT INTO product (ProductName, description, image_path, Quantity, Nr) VALUES (:title, :desc, :img, 10, 0)");
            
            $products = [
                [
                    'title' => 'GPU',
                    'desc' => 'The GPU is dedicated to rendering images, video, and animations. By handling visual workloads separately from the CPU, it delivers smoother graphics performance and faster rendering. High‑end GPUs are essential for gaming, video editing, and other demanding applications where speed and clarity matter.',
                    'img' => 'Photos/1678052-radeon-gpu-background-1920x1080_3_cropped-rotated.jpg'
                ],
                [
                    'title' => 'CPU',
                    'desc' => 'Often called the “brain” of the computer, the CPU executes instructions and manages system operations. Modern processors feature multiple cores and advanced multithreading, allowing them to handle complex tasks efficiently. A powerful CPU ensures responsive performance across everyday computing and professional workloads.',
                    'img' => 'Photos/2613900-amd-ryzen-9000-desktop-og.avif'
                ],
                [
                    'title' => 'RAM',
                    'desc' => 'RAM provides fast, temporary storage for data that the CPU is actively using. More RAM allows a system to multitask smoothly, keeping applications responsive without slowing down. While it works closely with the CPU, RAM capacity and speed are critical for overall system performance. Such aspects are improved upon by the DDR5 version.',
                    'img' => 'Photos/DDR5-RAM-and-Ryzen-Buyer-Beware-Twitter-1200x675.jpg'
                ]
            ];

            foreach ($products as $p) {
                $stmt->execute([
                    ':title' => $p['title'],
                    ':desc' => $p['desc'],
                    ':img' => $p['img']
                ]);
            }
        }
    }
}

?>
