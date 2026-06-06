<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

if(!isset($_GET['id'])) {
    header("Location: manage_users.php");
    exit();
}

$uid = intval($_GET['id']);

// Fetch User Info
$user_res = $conn->query("SELECT * FROM users WHERE id = $uid");
if($user_res->num_rows == 0) {
    echo "<h2>User not found</h2>";
    include 'footer.php';
    exit();
}
$user = $user_res->fetch_assoc();

// Fetch Order History
$orders_res = $conn->query("SELECT * FROM orders WHERE user_id = $uid ORDER BY order_date DESC");
?>

<div class="mb-4">
    <a href="manage_users.php" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
    <div class="d-flex justify-content-between align-items-center">
        <h2>User Profile: <?php echo htmlspecialchars($user['full_name']); ?></h2>
        <span class="badge bg-<?php echo $user['status'] == 'active' ? 'success' : 'danger'; ?> fs-6">
            <?php echo ucfirst($user['status']); ?>
        </span>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2 mb-3">Contact Information</h5>
                <p><strong>Email:</strong><br><?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Phone:</strong><br><?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?></p>
                <p><strong>Address:</strong><br><?php echo nl2br(htmlspecialchars($user['address'] ?? 'N/A')); ?></p>
                <p><strong>Registered On:</strong><br><?php echo date('M d, Y', strtotime($user['created_at'])); ?></p>
            </div>
        </div>
        
        <div class="card border-0 shadow-sm bg-primary text-white mb-4">
            <div class="card-body text-center py-4">
                <h6 class="opacity-75">Loyalty Points</h6>
                <h1 class="display-4 fw-bold mb-0"><?php echo $user['points']; ?></h1>
                <p class="small mb-0 opacity-75">Accumulated via purchases</p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent py-3">
                <h5 class="card-title mb-0"><i class="bi bi-receipt me-2"></i>Order History</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($orders_res->num_rows > 0): ?>
                                <?php while($order = $orders_res->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong></td>
                                    <td><?php echo date('M d, Y H:i', strtotime($order['order_date'])); ?></td>
                                    <td>
                                        <span class="badge rounded-pill bg-<?php echo $order['status'] == 'completed' ? 'success' : 'warning'; ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No orders found for this user.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
