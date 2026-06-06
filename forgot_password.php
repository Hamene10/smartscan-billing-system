<?php
include 'db_connect.php';
require_once 'utils/email_sender.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if user exists
    $stmt = $conn->prepare("SELECT full_name FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Store token in DB
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires_at);
        
        if ($stmt->execute()) {
            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/smart_scan_billing/reset_password.php?token=" . $token;
            if (sendPasswordResetEmail($email, $user['full_name'], $resetLink)) {
                $success = "Password reset link has been sent to your email.";
            } else {
                $error = "Failed to send reset email. Please try again later.";
            }
        } else {
            $error = "An error occurred. Please try again.";
        }
    } else {
        // For security, don't reveal if email exists, but here we can be helpful for a demo
        $error = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Smart Scan</title>
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
                <h3 class="fw-800">Forgot Password?</h3>
                <p class="text-muted">No worries! Enter your email and we'll send you a link to reset it.</p>
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
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" required placeholder="john@example.com">
                    </div>
                </div>
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-primary py-3">Send Reset Link <i class="bi bi-send ms-2"></i></button>
                    <a href="login.php" class="btn btn-outline-light border-0 text-muted small">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
