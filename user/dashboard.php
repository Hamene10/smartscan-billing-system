<?php
session_start();
require_once '../db_connect.php';
include 'header.php';
?>

<?php
$user_id = $_SESSION['user_id'];
$user_data = $conn->query("SELECT points FROM users WHERE id=$user_id")->fetch_assoc();
$current_points = $user_data['points'];
?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card bg-info text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0"><?php echo t('loyalty_points'); ?></h5>
                    <p class="mb-0 small text-white-50"><?php echo t('earn_points_msg'); ?></p>
                </div>
                <div class="text-end">
                    <h2 class="mb-0"><?php echo $current_points; ?></h2>
                    <span class="small"><?php echo t('available_points'); ?></span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-success">
    <h4><?php echo t('welcome'); ?>, <?php echo $_SESSION['user_name']; ?>!</h4>
    <p>Ready to shop faster? Click on <strong><?php echo t('scan_qr'); ?></strong> to start adding items to your cart.</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card text-center p-4">
            <h3><?php echo t('start_shopping'); ?></h3>
            <p>Scan product QR codes to add to cart.</p>
            <a href="scan.php" class="btn btn-lg btn-success"><?php echo t('scan_qr'); ?></a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-center p-4">
            <h3>Your Cart</h3>
            <p>Check your items before checkout.</p>
            <a href="cart.php" class="btn btn-lg btn-primary"><?php echo t('view_cart'); ?></a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
