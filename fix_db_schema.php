<?php
require_once 'db_connect.php';

function columnExists($conn, $table, $column) {
    $result = $conn->query("SHOW COLUMNS FROM `$table` LIKE '$column'");
    return $result && $result->num_rows > 0;
}

// Fix users table
if (!columnExists($conn, 'users', 'points')) {
    echo "Adding 'points' to 'users'...\n";
    $conn->query("ALTER TABLE users ADD COLUMN points INT DEFAULT 0");
} else {
    echo "'points' already exists in 'users'.\n";
}

// Fix orders table
if (!columnExists($conn, 'orders', 'points_earned')) {
    echo "Adding 'points_earned' to 'orders'...\n";
    $conn->query("ALTER TABLE orders ADD COLUMN points_earned INT DEFAULT 0");
} else {
    echo "'points_earned' already exists in 'orders'.\n";
}

if (!columnExists($conn, 'orders', 'points_redeemed')) {
    echo "Adding 'points_redeemed' to 'orders'...\n";
    $conn->query("ALTER TABLE orders ADD COLUMN points_redeemed INT DEFAULT 0");
} else {
    echo "'points_redeemed' already exists in 'orders'.\n";
}

echo "Database fix complete.\n";
?>
