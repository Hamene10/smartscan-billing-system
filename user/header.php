<?php
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
require_once '../utils/i18n.php';

// Handle Language Switch
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
    $current_url = strtok($_SERVER["REQUEST_URI"], '?');
    header("Location: $current_url");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Shop - Dashboard</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
                <i class="bi bi-cart3 me-2 text-primary"></i> Smart Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="userNavbar">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php"><?php echo t('dashboard'); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'scan.php' ? 'active' : ''; ?>" href="scan.php"><?php echo t('scan'); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'cart.php' ? 'active' : ''; ?>" href="cart.php"><?php echo t('view_cart'); ?></a></li>
                    <li class="nav-item"><a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'history.php' ? 'active' : ''; ?>" href="history.php"><?php echo t('history'); ?></a></li>
                    
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle btn btn-sm btn-outline-secondary px-2 py-1" href="#" id="langDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="bi bi-translate"></i> <?php echo strtoupper(isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en'); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=es">Español</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item ms-lg-3"><a class="btn btn-outline-danger btn-sm" href="../logout.php"><?php echo t('logout'); ?></a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container pb-5">
