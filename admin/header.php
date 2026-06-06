<?php
if(!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Smart Scan Billing</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../assets/css/style.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-end" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-transparent">Smart Scan <i class="bi bi-qr-code-scan"></i></div>
            <div class="list-group list-group-flush mt-3">
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2 sidebar-icon"></i> Dashboard
                </a>
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'add_product.php' ? 'active' : ''; ?>" href="add_product.php">
                    <i class="bi bi-plus-circle sidebar-icon"></i> Add Product
                </a>
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'manage_products.php' ? 'active' : ''; ?>" href="manage_products.php">
                    <i class="bi bi-box-seam sidebar-icon"></i> Products
                </a>
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'inventory_alerts.php' ? 'active' : ''; ?>" href="inventory_alerts.php">
                    <i class="bi bi-exclamation-triangle sidebar-icon"></i> Inventory Alerts
                </a>
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'sales_report.php' ? 'active' : ''; ?>" href="sales_report.php">
                    <i class="bi bi-graph-up sidebar-icon"></i> Sales Report
                </a>
                <a class="list-group-item list-group-item-action p-3 <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>" href="manage_users.php">
                    <i class="bi bi-people sidebar-icon"></i> Manage Users
                </a>
                <a class="list-group-item list-group-item-action p-3 text-danger" href="../logout.php">
                    <i class="bi bi-box-arrow-right sidebar-icon"></i> Logout
                </a>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <!-- Top Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-primary" id="sidebarToggle"><i class="bi bi-list"></i></button>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="#">Admin Portal</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <div class="container-fluid p-4">
