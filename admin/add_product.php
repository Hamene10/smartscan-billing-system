<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $expiry = $_POST['expiry'];
    
    // Image Upload Logic
    $image = "default_product.png";
    if(isset($_FILES['product_image']) && $_FILES['product_image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['product_image']['name'];
        $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if(in_array($file_ext, $allowed)) {
            $new_name = uniqid() . "." . $file_ext;
            $destination = "../uploads/" . $new_name;
            if(move_uploaded_file($_FILES['product_image']['tmp_name'], $destination)) {
                $image = $new_name;
            } else {
                $error = "Failed to upload image.";
            }
        } else {
            $error = "Invalid file type. Only JPG, JPEG, and PNG allowed.";
        }
    }

    if(empty($error)) {
        // Generate simplified Product ID/Code
        $product_code = "PROD-" . time(); 
        
        $stmt = $conn->prepare("INSERT INTO products (product_code, name, description, price, stock_quantity, expiry_date, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiss", $product_code, $name, $description, $price, $stock, $expiry, $image);
        
        if ($stmt->execute()) {
            $success = "Product added successfully! Product Code: " . $product_code;
        } else {
            $error = "Error adding product: " . $conn->error;
        }
    }
}
?>

<h2>Add New Product</h2>
<?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
<?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

<form method="POST" action="" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Product Image</label>
        <input type="file" name="product_image" class="form-control" accept=".jpg, .jpeg, .png">
        <small class="text-muted">Allowed: JPG, JPEG, PNG</small>
    </div>
    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Price</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Stock Quantity</label>
            <input type="number" name="stock" class="form-control" required>
        </div>
        <div class="col-md-4 mb-3">
            <label>Expiry Date</label>
            <input type="date" name="expiry" class="form-control" required>
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Add Product & Generate QR</button>
</form>

<?php include 'footer.php'; ?>
