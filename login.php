<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION['admin_id'])) {
    header("Location: admin/dashboard.php");
    exit();
}
if (isset($_SESSION['user_id'])) {
    header("Location: user/dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = $_POST['identifier'];
    $password = $_POST['password'];

    // Admin Login Check
    $stmt = $conn->prepare("SELECT id, username, password FROM admin WHERE username = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['username'];
            header("Location: admin/dashboard.php");
            exit();
        }
    }

    // User Login Check
    $stmt = $conn->prepare("SELECT id, full_name, password, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $identifier);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            if ($row['status'] == 'blocked') {
                $error = "Your account has been blocked. Please contact support.";
            } else {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['full_name'];
                header("Location: user/dashboard.php");
                exit();
            }
        }
    }

    $error = "Invalid Username/Email or Password";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Scan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="hero-blob blob-1"></div>
    <div class="hero-blob blob-2"></div>
    
    <div class="container d-flex justify-content-center">
        <div class="card glass-card auth-card fade-in-up">
            <div class="text-center mb-5">
                <a href="index.php" class="navbar-brand mb-4 d-inline-block">
                    <i class="bi bi-qr-code-scan me-2"></i> Smart Scan
                </a>
                <h3 class="fw-800">Welcome Back</h3>
                <p class="text-muted">Login to stay synced with your cart</p>
            </div>
            
            <?php if(isset($error)): ?>
                <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger small py-3 text-center mb-4" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Email or Username</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="identifier" class="form-control border-start-0" required placeholder="User email or Admin name">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="form-label text-muted small fw-bold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" required placeholder="••••••••">
                    </div>
                    <div class="text-end mt-2">
                        <a href="forgot_password.php" class="text-muted small text-decoration-none hover-primary">Forgot Password?</a>
                    </div>
                </div>
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-primary py-3">Sign In <i class="bi bi-box-arrow-in-right ms-2"></i></button>
                    <a href="index.php" class="btn btn-outline-light border-0 text-muted small">Return to Homepage</a>
                </div>
            </form>
            <div class="text-center mt-5 small">
                Don't have an account yet? <a href="register.php" class="fw-bold text-primary text-decoration-none">Create Account</a>
            </div>
        </div>
    </div>
</body>
</html>
