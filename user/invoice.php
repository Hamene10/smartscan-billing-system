<?php
session_start();
require_once '../db_connect.php';

if(!isset($_GET['id'])) die("Invalid Order ID");
$oid = $_GET['id'];

// Fetch Order Info
$order = $conn->query("SELECT orders.*, users.full_name, users.phone, users.email FROM orders JOIN users ON orders.user_id = users.id WHERE orders.id=$oid")->fetch_assoc();

// Fetch Items
$items = $conn->query("SELECT order_items.*, products.name, products.image, products.expiry_date FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id=$oid");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #<?php echo $oid; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0,0,0,0.15); margin-top: 20px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="invoice-box">
            <div class="d-flex justify-content-between">
                <div>
                    <h2>Smart Scan Billing</h2>
                    <p>123 Grocery Lane<br>Smart City, 560000</p>
                </div>
                <div>
                    <h4>INVOICE</h4>
                    <p>Order #: <?php echo $oid; ?><br>
                    Date: <?php echo $order['order_date']; ?></p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <h5>Bill To:</h5>
                    <p><strong><?php echo $order['full_name']; ?></strong><br>
                    <?php echo $order['email']; ?><br>
                    <?php echo $order['phone']; ?></p>
                </div>
            </div>
            
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Qty</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $items->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <?php $img = $row['image'] ? $row['image'] : 'default_product.png'; ?>
                            <div class="d-flex align-items-center">
                                <img src="../uploads/<?php echo $img; ?>" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px; border-radius: 3px;">
                                <div>
                                    <strong><?php echo $row['name']; ?></strong><br>
                                    <small class="text-muted">Expiry: <?php echo date('d M Y', strtotime($row['expiry_date'])); ?></small>
                                </div>
                            </div>
                        </td>
                        <td>$<?php echo $row['price']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td>$<?php echo number_format($row['price'] * $row['quantity'], 2); ?></td>
                    </tr>
                    <?php endwhile; ?>
                    <tr>
                        <th colspan="3" class="text-end">Grand Total</th>
                        <th>$<?php echo number_format($order['total_amount'], 2); ?></th>
                    </tr>
                </tbody>
            </table>
            
            <div class="text-center mt-4">
                <p>Thank you for shopping with us!</p>
                <button onclick="window.print()" class="btn btn-primary no-print">Print Bill</button>
                <a href="dashboard.php" class="btn btn-secondary no-print">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
