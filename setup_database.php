<?php
include_once 'DatabaseSetup.php';

try {
    $setup = new DatabaseSetup();
    $setup->initTables();
    $setup->seedData();
    echo "Database and tables initialized successfully.";
} catch (Exception $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>
