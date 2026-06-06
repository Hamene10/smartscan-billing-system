<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

// Remove item
if(isset($_GET['remove'])) {
    $pid = $_GET['remove'];
    unset($_SESSION['cart'][$pid]);
    echo "<script>window.location='cart.php';</script>";
}

// Proceed to checkout logic is handled by button link
?>

<h2>Your Cart</h2>

<?php if(empty($_SESSION['cart'])): ?>
    <div class="alert alert-warning">Your cart is empty. <a href="scan.php">Scan items</a></div>
<?php else: ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Expiry</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $grand_total = 0;
            foreach($_SESSION['cart'] as $pid => $qty):
                $row = $conn->query("SELECT * FROM products WHERE id=$pid")->fetch_assoc();
                $total = $row['price'] * $qty;
                $grand_total += $total;
            ?>
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td>
                    <?php $img = $row['image'] ? $row['image'] : 'default_product.png'; ?>
                    <img src="../uploads/<?php echo $img; ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                </td>
                <td>
                    <?php 
                        $expiry = $row['expiry_date'];
                        $today = date('Y-m-d');
                        $badge_class = ($expiry < $today) ? 'text-danger fw-bold' : '';
                        echo "<span class='$badge_class'>" . date('d M Y', strtotime($expiry)) . "</span>";
                    ?>
                </td>
                <td>$<?php echo $row['price']; ?></td>
                <td><?php echo $qty; ?></td>
                <td>$<?php echo number_format($total, 2); ?></td>
                <td><a href="cart.php?remove=<?php echo $pid; ?>" class="btn btn-danger btn-sm">Remove</a></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                <td><strong>$<?php echo number_format($grand_total, 2); ?></strong></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    
    <div class="text-end">
        <a href="scan.php" class="btn btn-secondary">Scan More</a>
        <a href="checkout.php" class="btn btn-success btn-lg">Checkout</a>
    </div>
<?php endif; ?>

<?php include 'footer.php'; ?>
