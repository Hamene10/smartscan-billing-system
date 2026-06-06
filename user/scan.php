<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

$msg = '';

// Add to cart logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cart_product_id'])) {
    $pid = $_POST['cart_product_id'];
    $qty = $_POST['quantity'];
    
    // Check stock
    $check = $conn->query("SELECT * FROM products WHERE id=$pid")->fetch_assoc();
    if($check['stock_quantity'] >= $qty) {
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Check if item exists in cart
        if(isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] += $qty;
        } else {
            $_SESSION['cart'][$pid] = $qty;
        }
        $msg = "<div class='alert alert-success'>Product added to cart!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Insufficient stock!</div>";
    }
}

// Search/Scan Logic
$product = null;
if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        $msg = "<div class='alert alert-danger'>Product not found.</div>";
    }
}
?>

<h2><?php echo t('scan_qr'); ?></h2>
<?php echo $msg; ?>

<div class="card mb-4">
    <div class="card-body">
        <h5>Simulate Scanner</h5>
        <form method="GET" action="">
            <div class="input-group">
                <input type="text" name="code" class="form-control" placeholder="Enter QR Code / Product ID" required autofocus>
                <button class="btn btn-primary" type="submit">Scan</button>
            </div>
            <small class="text-muted">In a real app, this would be auto-filled by a camera JS library.</small>
        </form>
    </div>
</div>

<?php if($product): ?>
<div class="card border-primary">
    <div class="card-header bg-primary text-white">Product Details</div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <?php $img = $product['image'] ? $product['image'] : 'default_product.png'; ?>
                <img src="../uploads/<?php echo $img; ?>" class="img-fluid rounded" style="width: 150px; height: 150px; object-fit: cover; border: 1px solid #ddd;">
            </div>
            <div class="col-md-9">
                <h3><?php echo $product['name']; ?></h3>
                <p><?php echo $product['description']; ?></p>
                <h4 class="text-success">$<?php echo $product['price']; ?></h4>
                <p>Stock: <?php echo $product['stock_quantity']; ?></p>
                <p>
                    <?php echo t('expiry_date'); ?>: 
                    <?php 
                        $expiry = $product['expiry_date'];
                        $today = date('Y-m-d');
                        $near_expiry = date('Y-m-d', strtotime('+30 days'));
                        
                        $badge_class = 'bg-success';
                        if($expiry < $today) {
                            $badge_class = 'bg-danger';
                        } elseif ($expiry <= $near_expiry) {
                            $badge_class = 'bg-warning text-dark';
                        }
                    ?>
                    <span class="badge <?php echo $badge_class; ?>">
                        <?php echo date('d M Y', strtotime($expiry)); ?>
                        <?php if($expiry < $today) echo " (Expired)"; ?>
                    </span>
                </p>
            </div>
        </div>
        
        <form method="POST" action="">
            <input type="hidden" name="cart_product_id" value="<?php echo $product['id']; ?>">
            <div class="row">
                <div class="col-md-3">
                    <label>Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>">
                </div>
                <div class="col-md-3 align-self-end">
                    <button type="submit" class="btn btn-success">Add to Cart</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>

<div class="mt-4">
    <h4>Available Codes (For Testing)</h4>
    <ul>
    <?php
    $codes = $conn->query("SELECT product_code, name, expiry_date FROM products LIMIT 5");
    while($c = $codes->fetch_assoc()) {
        echo "<li>{$c['product_code']} ({$c['name']}) - Expiry: {$c['expiry_date']}</li>";
    }
    ?>
    </ul>
</div>

<?php include 'footer.php'; ?>
