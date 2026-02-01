<?php
include_once 'Database.php';

try {
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "CREATE TABLE IF NOT EXISTS product (
        ProductID INT AUTO_INCREMENT PRIMARY KEY,
        ProductName VARCHAR(255) NOT NULL,
        Quantity INT DEFAULT 0,
        Nr INT DEFAULT 0
    )";
    $conn->exec($sql);
    echo "Table 'product' checked/created.<br>";


    $checkCol = $conn->prepare("SHOW COLUMNS FROM product LIKE 'description'");
    $checkCol->execute();
    if ($checkCol->rowCount() == 0) {
        $conn->exec("ALTER TABLE product ADD COLUMN description TEXT");
        echo "Column 'description' added.<br>";
    }

    $checkCol = $conn->prepare("SHOW COLUMNS FROM product LIKE 'image_path'");
    $checkCol->execute();
    if ($checkCol->rowCount() == 0) {
        $conn->exec("ALTER TABLE product ADD COLUMN image_path VARCHAR(255)");
        echo "Column 'image_path' added.<br>";
    }


    $checkCol = $conn->prepare("SHOW COLUMNS FROM product LIKE 'created_at'");
    $checkCol->execute();
    if ($checkCol->rowCount() == 0) {
        $conn->exec("ALTER TABLE product ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        echo "Column 'created_at' added.<br>";
    }


    $checkCol = $conn->prepare("SHOW COLUMNS FROM user LIKE 'last_seen'");
    $checkCol->execute();
    if ($checkCol->rowCount() == 0) {
        $conn->exec("ALTER TABLE user ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL");
        echo "Column 'last_seen' added.<br>";
    }

    $checkCol2 = $conn->prepare("SHOW COLUMNS FROM user LIKE 'force_logout'");
    $checkCol2->execute();
    if ($checkCol2->rowCount() == 0) {
        $conn->exec("ALTER TABLE user ADD COLUMN force_logout TINYINT(1) DEFAULT 0");
        echo "Column 'force_logout' added.<br>";
    }

    $checkProd = $conn->query("SELECT count(*) FROM product")->fetchColumn();
    if ($checkProd == 0) {
        $stmt = $conn->prepare("INSERT INTO product (ProductName, description, image_path, Quantity, Nr) VALUES (:title, :desc, :img, 10, 0)");
        
        $stmt->execute([
            ':title' => 'GPU',
            ':desc' => 'The GPU is dedicated to rendering images, video, and animations. By handling visual workloads separately from the CPU, it delivers smoother graphics performance and faster rendering. High‑end GPUs are essential for gaming, video editing, and other demanding applications where speed and clarity matter.',
            ':img' => 'Photos/1678052-radeon-gpu-background-1920x1080_3_cropped-rotated.jpg'
        ]);
        
        $stmt->execute([
            ':title' => 'CPU',
            ':desc' => 'Often called the “brain” of the computer, the CPU executes instructions and manages system operations. Modern processors feature multiple cores and advanced multithreading, allowing them to handle complex tasks efficiently. A powerful CPU ensures responsive performance across everyday computing and professional workloads.',
            ':img' => 'Photos/2613900-amd-ryzen-9000-desktop-og.avif'
        ]);
        
        $stmt->execute([
            ':title' => 'RAM',
            ':desc' => 'RAM provides fast, temporary storage for data that the CPU is actively using. More RAM allows a system to multitask smoothly, keeping applications responsive without slowing down. While it works closely with the CPU, RAM capacity and speed are critical for overall system performance. Such aspects are improved upon by the DDR5 version.',
            ':img' => 'Photos/DDR5-RAM-and-Ryzen-Buyer-Beware-Twitter-1200x675.jpg'
        ]);
        echo "Initial products (GPU, CPU, RAM) inserted.<br>";
    } else {
        echo "Products already exist, skipping initial population.<br>";
    }

    echo "Schema update complete.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
