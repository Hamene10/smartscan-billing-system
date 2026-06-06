<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiry = $_POST['expiry'];
    
    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=?, expiry_date=? WHERE id=?");
    $stmt->bind_param("ssdiss", $name, $description, $price, $stock, $expiry, $id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Updated successfully'); window.location='manage_products.php';</script>";
    } else {
        echo "<div class='alert alert-danger'>Error updating product.</div>";
    }
}
?>

<h2>Edit Product</h2>
<form method="POST" action="">
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $row['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"><?php echo $row['description']; ?></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $row['price']; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="<?php echo $row['stock_quantity']; ?>" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry" class="form-control" value="<?php echo $row['expiry_date']; ?>" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Update Product</button>
</form>

<?php include 'footer.php'; ?>
