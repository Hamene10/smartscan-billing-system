<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

// Handle Delete
if(isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$id");
    echo "<script>window.location='manage_products.php';</script>";
}
?>

<h2>Manage Products</h2>
<table class="table table-striped mt-3">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>QR Code</th>
            <th>Name</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Expiry</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $result = $conn->query("SELECT * FROM products ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td>
                <?php $img = $row['image'] ? $row['image'] : 'default_product.png'; ?>
                <img src="../uploads/<?php echo $img; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
            </td>
            <td>
                <!-- Generate QR Code using API for reliable display -->
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=50x50&data=<?php echo $row['product_code']; ?>" alt="QR">
                <br><small><?php echo $row['product_code']; ?></small>
            </td>
            <td><?php echo $row['name']; ?></td>
            <td>$<?php echo $row['price']; ?></td>
            <td><?php echo $row['stock_quantity']; ?></td>
            <td><?php echo $row['expiry_date']; ?></td>
            <td>
                <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="manage_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include 'footer.php'; ?>
