<?php
session_start();
require_once '../db_connect.php';
include 'header.php';
?>

<h2>Inventory Alerts</h2>

<h4 class="text-danger mt-4">Low Stock Alerts (Less than 5)</h4>
<table class="table table-bordered">
    <thead>
        <tr><th>Product</th><th>Current Stock</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php
        $low_stock = $conn->query("SELECT * FROM products WHERE stock_quantity < 5");
        if($low_stock->num_rows > 0):
            while($row = $low_stock->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><span class="badge bg-danger"><?php echo $row['stock_quantity']; ?></span></td>
            <td><a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Restock</a></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="3">No low stock alerts.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h4 class="text-warning mt-4">Near Expiry Alerts (Next 7 Days)</h4>
<table class="table table-bordered">
    <thead>
        <tr><th>Product</th><th>Expiry Date</th><th>Action</th></tr>
    </thead>
    <tbody>
        <?php
        $expiry = $conn->query("SELECT * FROM products WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY) AND expiry_date >= CURDATE()");
        if($expiry->num_rows > 0):
            while($row = $expiry->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['name']; ?></td>
            <td><span class="badge bg-warning text-dark"><?php echo $row['expiry_date']; ?></span></td>
            <td><a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Manage</a></td>
        </tr>
        <?php endwhile; else: ?>
        <tr><td colspan="3">No expiry alerts.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
