<?php
require_once 'db_connect.php';

// Check if status column exists in users table
$check = $conn->query("SHOW COLUMNS FROM users LIKE 'status'");
if($check->num_rows == 0) {
    // Add status column if not exists
    $sql = "ALTER TABLE users ADD COLUMN status ENUM('active', 'blocked') DEFAULT 'active' AFTER points";
    if ($conn->query($sql) === TRUE) {
        echo "Database updated successfully. 'status' column added to 'users' table.";
    } else {
        echo "Error updating database: " . $conn->error;
    }
} else {
    echo "Column 'status' already exists in 'users' table.";
}
?>
