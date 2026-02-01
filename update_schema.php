<?php
include_once 'DatabaseSetup.php';

try {
    $setup = new DatabaseSetup();
    $setup->initTables();
    $setup->seedData();
    echo "Schema update and data seeding complete.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
