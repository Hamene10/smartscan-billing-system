<?php
include 'db_connect.php';

if (!isset($_GET['token'])) {
    header("Location: login.php");
    exit();
}

$token = $_GET['token'];
$stmt = $conn->prepare("SELECT email, expires_at FROM password_resets WHERE token = ?");
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Invalid or expired token.");
}

$row = $result->fetch_assoc();
if (strtotime($row['expires_at']) < time()) {
    die("Token has expired.");
}

$email = $row['email'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['password'];
    
    // Validate password (example: min 8 chars with complexity)
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        // Update user password
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        
        if ($stmt->execute()) {
            // Delete token
            $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            
            $success = "Password reset successful! You can now <a href='login.php'>sign in</a>.";
        } else {
            $error = "Failed to update password. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Smart Scan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>

    <div class="container d-flex justify-content-center py-5">
        <div class="card glass-card auth-card fade-in-up">
            <div class="text-center mb-5">
                <a href="index.php" class="navbar-brand mb-4 d-inline-block">
                    <i class="bi bi-qr-code-scan me-2"></i> Smart Scan
                </a>
                <h3 class="fw-800">Set New Password</h3>
                <p class="text-muted">Enter a strong password to protect your account.</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger small py-3 text-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($success)): ?>
                <div class="alert alert-success bg-success bg-opacity-10 border-0 text-success small py-3 text-center mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i> <?php echo $success; ?>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="mb-5">
                        <label class="form-label text-muted small fw-bold">New Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                            <input type="password" name="password" class="form-control border-start-0" required placeholder="At least 8 characters" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="Password must be at least 8 characters and include uppercase, lowercase, number, and special character">
                        </div>
                    </div>
                    <div class="d-grid gap-3">
                        <button type="submit" class="btn btn-primary py-3">Update Password <i class="bi bi-check-circle ms-2"></i></button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
