<?php
require_once 'db_connect.php';

// Update users table for points
$conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS points INT DEFAULT 0");

// Update orders table for points earned/redeemed
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS points_earned INT DEFAULT 0");
$conn->query("ALTER TABLE orders ADD COLUMN IF NOT EXISTS points_redeemed INT DEFAULT 0");

echo "Database updated successfully for points system.\n";
?>
