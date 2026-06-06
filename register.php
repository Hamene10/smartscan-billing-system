<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['name']);
    $email = $_POST['email'];
    $raw_password = $_POST['password'];
    // Server-side password validation: at least 8 chars, includes uppercase, lowercase, number, special char
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $raw_password)) {
        $error = "Password must be at least 8 characters and include uppercase, lowercase, number, and special character.";
    } else {
        $password = password_hash($raw_password, PASSWORD_DEFAULT);
    }

    // Validation: Name should not contain numbers
    if (!preg_match("/^[a-zA-Z\s]*$/", $full_name)) {
        $error = "Name should only contain letters and spaces.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $full_name, $email, $password);

        try {
            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Registration failed. Please try again.";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $error = "This email is already registered. Please use a different email or sign in.";
            } else {
                $error = "Registration failed: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - Smart Scan</title>
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
                <h3 class="fw-800">Join the Future</h3>
                <p class="text-muted">Start scanning your way to a faster checkout</p>
            </div>

            <?php if(isset($error)): ?>
                <div class="alert alert-danger bg-danger bg-opacity-10 border-0 text-danger small py-3 text-center mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Full Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0" required 
                               pattern="[a-zA-Z\s]+" title="Name should only contain letters and spaces" 
                               placeholder="e.g. John Doe">
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label text-muted small fw-bold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-envelope text-muted"></i></span>
                        <input type="email" name="email" class="form-control border-start-0" required placeholder="john@example.com">
                    </div>
                </div>
                <div class="mb-5">
                    <label class="form-label text-muted small fw-bold">Choose Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-shield-lock text-muted"></i></span>
                        <input type="password" name="password" class="form-control border-start-0" required placeholder="At least 8 characters" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}" title="Password must be at least 8 characters and include uppercase, lowercase, number, and special character">
                    </div>
                </div>
                <div class="d-grid gap-3">
                    <button type="submit" class="btn btn-primary py-3">Create Account <i class="bi bi-arrow-right-circle ms-2"></i></button>
                </div>
            </form>
            <div class="text-center mt-5 small">
                Already part of Smart Scan? <a href="login.php" class="fw-bold text-primary text-decoration-none">Sign In</a>
            </div>
        </div>
    </div>
</body>
</html>
