<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

// Handle Status Toggle
if(isset($_GET['toggle_status']) && isset($_GET['id'])) {
    $uid = intval($_GET['id']);
    $new_status = $_GET['toggle_status'] == 'active' ? 'blocked' : 'active';
    $conn->query("UPDATE users SET status = '$new_status' WHERE id = $uid");
    header("Location: manage_users.php?msg=Status updated");
    exit();
}

// Fetch Users
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$sql = "SELECT id, full_name, email, phone, points, status FROM users";
if($search) {
    $sql .= " WHERE full_name LIKE '%$search%' OR email LIKE '%$search%'";
}
$result = $conn->query($sql);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Users</h2>
    <form class="d-flex" style="max-width: 300px;">
        <input class="form-control me-2" type="search" name="search" placeholder="Search users..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-primary btn-sm" type="submit">Search</button>
    </form>
</div>

<?php if(isset($_GET['msg'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo htmlspecialchars($_GET['msg']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Points</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
                            <td><span class="badge bg-info text-dark"><?php echo $row['points']; ?> pts</span></td>
                            <td>
                                <span class="badge bg-<?php echo $row['status'] == 'active' ? 'success' : 'danger'; ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="user_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary me-1">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="manage_users.php?id=<?php echo $row['id']; ?>&toggle_status=<?php echo $row['status']; ?>" 
                                   class="btn btn-sm btn-<?php echo $row['status'] == 'active' ? 'danger' : 'success'; ?> px-3"
                                   onclick="return confirm('Change user status to <?php echo $row['status'] == 'active' ? 'Blocked' : 'Active'; ?>?')">
                                    <?php echo $row['status'] == 'active' ? 'Block' : 'Unblock'; ?>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
