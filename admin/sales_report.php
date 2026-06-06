<?php
session_start();
require_once '../db_connect.php';
include 'header.php';
?>

<h2>Sales Report</h2>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Total Amount</th>
            <th>Date</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $orders = $conn->query("SELECT orders.*, users.full_name FROM orders JOIN users ON orders.user_id = users.id ORDER BY orders.order_date DESC");
        while($row = $orders->fetch_assoc()):
        ?>
        <tr>
            <td>#<?php echo $row['id']; ?></td>
            <td><?php echo $row['full_name']; ?></td>
            <td>$<?php echo $row['total_amount']; ?></td>
            <td><?php echo $row['order_date']; ?></td>
            <td>
                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $row['id']; ?>">View Items</button>
                
                <!-- Modal -->
                <div class="modal fade" id="orderModal<?php echo $row['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order #<?php echo $row['id']; ?> Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <ul>
                                <?php
                                $oid = $row['id'];
                                $items = $conn->query("SELECT order_items.*, products.name FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = $oid");
                                while($item = $items->fetch_assoc()) {
                                    echo "<li>{$item['name']} - {$item['quantity']} x \${$item['price']}</li>";
                                }
                                ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
