<?php
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connect to MySQL without database selected
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS projectbase");
    echo "Database 'projectbase' created successfully or already exists.<br>";

    // Connect to the specific database
    $pdo->exec("USE projectbase");

    // DROP TABLE to ensure fresh schema (WARNING: DELETES DATA)
    $pdo->exec("DROP TABLE IF EXISTS user");

    // Create user table
    $sql = "CREATE TABLE user (
        id INT AUTO_INCREMENT PRIMARY KEY,
        Fullname VARCHAR(100) NOT NULL,
        Email VARCHAR(100) NOT NULL UNIQUE,
        Password VARCHAR(255) NOT NULL,
        Role INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql);
    echo "Table 'user' created successfully or already exists.<br>";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>
