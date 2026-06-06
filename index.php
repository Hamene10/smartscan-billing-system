<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Scan - futuristic Grocery Billing</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg glass-nav fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <i class="bi bi-qr-code-scan me-2"></i> Smart Scan
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="user/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="user/scan.php">Start Scanning</a></li>
                    <?php elseif(isset($_SESSION['admin_id'])): ?>
                        <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="admin/dashboard.php">Admin Panel</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="register.php">Get Started <i class="bi bi-arrow-right ms-2"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-blob blob-1"></div>
        <div class="hero-blob blob-2"></div>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content fade-in-up">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-3">
                        <i class="bi bi-stars me-1"></i> Next-Gen Billing System
                    </span>
                    <h1 class="hero-title">Smart Grocery <br>Billing <span class="text-primary">Made Simple</span></h1>
                    <p class="hero-subtitle">Simplify your shopping journey. Scan items with your phone, manage your cart in real-time, and checkout instantly.</p>
                    
                    <div class="d-flex flex-wrap gap-3 mb-5">
                        <?php if(!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])): ?>
                            <a href="register.php" class="btn btn-primary btn-lg px-4">Create Account</a>
                            <a href="login.php" class="btn btn-outline-primary btn-lg px-4">Sign In</a>
                        <?php else: ?>
                             <a href="user/scan.php" class="btn btn-primary btn-lg px-5">Start Scanning Now</a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="row g-4 pt-4 border-top">
                        <div class="col-sm-4">
                            <h4 class="fw-800 mb-1">0%</h4>
                            <p class="text-muted small mb-0">Waiting Time</p>
                        </div>
                        <div class="col-sm-4">
                            <h4 class="fw-800 mb-1">Secure</h4>
                            <p class="text-muted small mb-0">Digital Payments</p>
                        </div>
                        <div class="col-sm-4">
                            <h4 class="fw-800 mb-1">Sync</h4>
                            <p class="text-muted small mb-0">Real-time Cart</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-image-container fade-in-up" style="animation-delay: 0.2s;">
                    <div class="position-relative">
                        <img src="assets/images/hero.png" alt="Smart Scan Dashboard" class="hero-image float-anim">
                        <!-- Decorative element -->
                        <div class="glass-card position-absolute p-3 d-none d-md-block" style="top: 20%; left: -10%; z-index: 3; max-width: 180px;">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <div class="bg-success rounded-circle p-1"><i class="bi bi-check2 text-white small"></i></div>
                                <span class="small fw-600">Item Added</span>
                            </div>
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section bg-white">
        <div class="container">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-800">Everything you need for <span class="text-primary">Fast Checkout</span></h2>
                <p class="text-muted">A complete ecosystem designed to make grocery shopping seamless.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 fade-in-up" style="animation-delay: 0.1s;">
                    <div class="card feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4>Instant Scan</h4>
                        <p class="text-muted">Just point your camera at the QR code and the item is added to your cart instantly.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in-up" style="animation-delay: 0.2s;">
                    <div class="card feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h4>Wallet System</h4>
                        <p class="text-muted">Pre-load your wallet or pay directly. Secure and encrypted transactions always.</p>
                    </div>
                </div>
                <div class="col-md-4 fade-in-up" style="animation-delay: 0.3s;">
                    <div class="card feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h4>Digital Invoice</h4>
                        <p class="text-muted">Get your bills instantly on your phone. environmentally friendly and easy to track.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4 mb-5">
                <div class="col-lg-4">
                    <a class="navbar-brand d-flex align-items-center mb-3" href="#">
                        <i class="bi bi-qr-code-scan me-2"></i> Smart Scan
                    </a>
                    <p class="text-muted">Revolutionizing the way people shop for groceries with cutting-edge scanning technology.</p>
                </div>
                <div class="col-lg-2 offset-lg-2">
                    <h6 class="fw-bold mb-3">Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="login.php" class="text-muted text-decoration-none small">Login</a></li>
                        <li><a href="register.php" class="text-muted text-decoration-none small">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6 class="fw-bold mb-3">Contact</h6>
                    <p class="text-muted small">Support: support@smartscan.com</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-primary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-top pt-4 text-center">
                 <p class="mb-0 text-muted small">&copy; <?php echo date('Y'); ?> Smart Scan Billing. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.querySelector('.navbar').classList.add('scrolled');
            } else {
                document.querySelector('.navbar').classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
