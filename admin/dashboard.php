<?php
session_start();
require_once '../db_connect.php';
include 'header.php';

// Fetch stats
$prod_count = $conn->query("SELECT count(*) as count FROM products")->fetch_assoc()['count'];
$low_stock = $conn->query("SELECT count(*) as count FROM products WHERE stock_quantity < 5")->fetch_assoc()['count'];
$expiry_alert = $conn->query("SELECT count(*) as count FROM products WHERE expiry_date <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)")->fetch_assoc()['count'];
$sales = $conn->query("SELECT SUM(total_amount) as total FROM orders WHERE status='completed'")->fetch_assoc()['total'];

// Fetch Sales Trend (Last 7 Days)
$sales_trend_data = [];
$sales_trend_labels = [];
$trend_sql = "SELECT DATE(order_date) as date, SUM(total_amount) as daily_total 
              FROM orders 
              WHERE status='completed' AND order_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
              GROUP BY DATE(order_date) 
              ORDER BY date ASC";
$trend_res = $conn->query($trend_sql);
while($row = $trend_res->fetch_assoc()) {
    $sales_trend_labels[] = date('M d', strtotime($row['date']));
    $sales_trend_data[] = (float)$row['daily_total'];
}

// Fetch Top 5 Selling Products
$top_products_labels = [];
$top_products_data = [];
$top_sql = "SELECT p.name, SUM(oi.quantity) as total_qty 
            FROM order_items oi 
            JOIN products p ON oi.product_id = p.id 
            GROUP BY p.id 
            ORDER BY total_qty DESC LIMIT 5";
$top_res = $conn->query($top_sql);
while($row = $top_res->fetch_assoc()) {
    $top_products_labels[] = $row['name'];
    $top_products_data[] = (int)$row['total_qty'];
}
?>

<h2>Dashboard</h2>
<div class="row mt-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm bg-primary text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75">Total Products</h6>
                        <h2 class="card-text mb-0"><?php echo $prod_count; ?></h2>
                    </div>
                    <i class="bi bi-box-seam fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm bg-warning text-dark mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75">Low Stock</h6>
                        <h2 class="card-text mb-0"><?php echo $low_stock; ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm bg-danger text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75">Near Expiry</h6>
                        <h2 class="card-text mb-0"><?php echo $expiry_alert; ?></h2>
                    </div>
                    <i class="bi bi-clock-history fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm bg-success text-white mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title opacity-75">Total Sales</h6>
                        <h2 class="card-text mb-0">$<?php echo number_format($sales ?? 0, 2); ?></h2>
                    </div>
                    <i class="bi bi-currency-dollar fs-1 opacity-25"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="card-title"><i class="bi bi-graph-up me-2"></i>Sales Trend (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <canvas id="salesTrendChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="card-title"><i class="bi bi-pie-chart me-2"></i>Top 5 Products</h5>
            </div>
            <div class="card-body">
                <canvas id="topProductsChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="mt-2 row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0 pt-3">
                <h5 class="card-title"><i class="bi bi-receipt me-2"></i>Recent Orders</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT orders.id, users.full_name, orders.total_amount, orders.order_date 
                                FROM orders JOIN users ON orders.user_id = users.id 
                                ORDER BY orders.order_date DESC LIMIT 5";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()):
                        ?>
                        <tr>
                            <td>#<?php echo $row['id']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td>$<?php echo number_format($row['total_amount'], 2); ?></td>
                            <td><?php echo date('M d, Y H:i', strtotime($row['order_date'])); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Sales Trend Chart
const ctxTrend = document.getElementById('salesTrendChart').getContext('2d');
new Chart(ctxTrend, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($sales_trend_labels); ?>,
        datasets: [{
            label: 'Daily Sales ($)',
            data: <?php echo json_encode($sales_trend_data); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 3,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    display: true,
                    drawBorder: false
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Top Products Chart
const ctxTop = document.getElementById('topProductsChart').getContext('2d');
new Chart(ctxTop, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($top_products_labels); ?>,
        datasets: [{
            data: <?php echo json_encode($top_products_data); ?>,
            backgroundColor: [
                '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
            ],
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    boxWidth: 12,
                    padding: 20
                }
            }
        }
    }
});
</script>

<?php include 'footer.php'; ?>
