<?php
session_start();
require_once '../db_connect.php';
include 'header.php';
$uid = $_SESSION['user_id'];
?>

<h2>My Purchase History</h2>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Receipt</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $conn->query("SELECT * FROM orders WHERE user_id=$uid ORDER BY order_date DESC");
        while($row = $orders->fetch_assoc()):
        ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
            <td>$<?php echo $row['total_amount']; ?></td>
            <td><a href="invoice.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-info" target="_blank">View Bill</a></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
