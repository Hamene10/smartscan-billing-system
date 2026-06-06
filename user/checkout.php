<?php
session_start();
require_once '../db_connect.php';
require_once '../utils/pdf_generator.php';
require_once '../utils/email_sender.php';
include 'header.php';

if(empty($_SESSION['cart'])) {
    echo "<script>window.location='scan.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT points FROM users WHERE id=$user_id")->fetch_assoc();
$available_points = $user['points'];

$grand_total = 0;
foreach($_SESSION['cart'] as $pid => $qty) {
    $row = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
    $grand_total += $row['price'] * $qty;
}

$redeem_points = 0;
$discount = 0;
if (isset($_POST['redeem']) && $_POST['redeem'] > 0) {
    $redeem_points = min($_POST['redeem'], $available_points);
    $discount = $redeem_points / 10; // 1 point = $0.10, or 10 points = $1
    if ($discount > $grand_total) {
        $discount = $grand_total;
        $redeem_points = $discount * 10;
    }
}
$payable_amount = $grand_total - $discount;

if(isset($_POST['confirm'])) {
    $redeem_points_used = isset($_POST['redeemed_points']) ? (int)$_POST['redeemed_points'] : 0;
    $final_total = $grand_total - ($redeem_points_used / 10);
    
    // Create Order
    $points_earned = floor($final_total / 10); // 1 point per $10 spent
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, points_earned, points_redeemed) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idii", $user_id, $final_total, $points_earned, $redeem_points_used);
    $stmt->execute();
    $order_id = $conn->insert_id;
    
    // Update User Points
    $conn->query("UPDATE users SET points = points - $redeem_points_used + $points_earned WHERE id=$user_id");
    
    // Insert Order Items and Update Stock
    foreach($_SESSION['cart'] as $pid => $qty) {
        $row = $conn->query("SELECT price FROM products WHERE id=$pid")->fetch_assoc();
        $price = $row['price'];
        
        $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES ($order_id, $pid, $qty, $price)");
        $conn->query("UPDATE products SET stock_quantity = stock_quantity - $qty WHERE id=$pid");
    }
    
    // Generate PDF Invoice
    $pdfPath = generateInvoicePdf($order_id);
    
    // Send Email
    $user_info = $conn->query("SELECT full_name, email FROM users WHERE id=$user_id")->fetch_assoc();
    sendInvoiceEmail($user_info['email'], $user_info['full_name'], $pdfPath, $order_id);
    
    // Clear Cart
    unset($_SESSION['cart']);
    
    // Redirect to Invoice
    echo "<script>window.location='invoice.php?id=$order_id';</script>";
}
?>

<h2><?php echo t('checkout'); ?></h2>
<div class="card">
    <div class="card-body">
        <h4><?php echo t('order_summary'); ?></h4>
        <p><?php echo t('total_items'); ?>: <?php echo count($_SESSION['cart']); ?></p>
        <p><?php echo t('subtotal'); ?>: $<?php echo number_format($grand_total, 2); ?></p>
        
        <form method="POST" id="checkout-form">
            <div class="mb-3">
                <label><?php echo t('available_points'); ?>: <strong><?php echo $available_points; ?></strong> (10 pts = $1)</label>
                <div class="input-group">
                    <input type="number" name="redeem" id="redeem-input" class="form-control" placeholder="<?php echo t('points_to_redeem'); ?>" min="0" max="<?php echo $available_points; ?>" value="<?php echo isset($_POST['redeem']) ? $_POST['redeem'] : 0; ?>">
                    <button type="submit" class="btn btn-outline-primary"><?php echo t('apply_points'); ?></button>
                </div>
            </div>
            
            <?php if($discount > 0): ?>
                <p class="text-success">Discount: -$<?php echo number_format($discount, 2); ?></p>
            <?php endif; ?>
            
            <h3 class="text-primary"><?php echo t('payable_amount'); ?>: $<?php echo number_format($payable_amount, 2); ?></h3>
            <input type="hidden" name="redeemed_points" value="<?php echo $redeem_points; ?>">
        
        <hr>
        <h5>Select Payment Method (Simulation)</h5>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment" checked>
            <label class="form-check-label">Credit/Debit Card</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment">
            <label class="form-check-label">UPI / QR Payment</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="payment">
            <label class="form-check-label">Cash</label>
        </div>
        
            <button type="submit" name="confirm" class="btn btn-success w-100 btn-lg"><?php echo t('confirm_payment'); ?></button>
        </form>
        
        <div class="alert alert-info mt-2">
            Clicking Confirm will create the order, update stock, and generate the final bill.
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
