<?php
require_once 'db_connect.php';

// Check if column exists
$check = $conn->query("SHOW COLUMNS FROM products LIKE 'image'");
if($check->num_rows == 0) {
    // Add column if not exists
    $sql = "ALTER TABLE products ADD COLUMN image VARCHAR(255) DEFAULT 'default_product.png' AFTER name";
    if ($conn->query($sql) === TRUE) {
        echo "Database updated successfully. 'image' column added to 'products' table.";
    } else {
        echo "Error updating database: " . $conn->error;
    }
} else {
    echo "Column 'image' already exists.";
}
?>
