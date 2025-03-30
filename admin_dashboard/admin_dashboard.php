<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Golden Mart Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/admin.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Poppins', sans-serif;
        }

        #content {
            transition: all 0.3s;
            width: 100%;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            background: linear-gradient(to right, #7D3C98, #9B59B6);
            color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.15);
        }

        .card h5 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .card h3 {
            font-size: 2rem;
            font-weight: bold;
        }

        footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            font-size: 0.9rem;
        }

        .dashboard-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5B2C6F;
            margin-bottom: 20px;
        }

        .chart-container {
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 30px;
            transition: transform 0.3s;
        }

        .chart-container:hover {
            transform: translateY(-5px);
        }

        canvas {
            width: 100% !important;
            height: 300px !important;
        }

        .secondary-nav {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            z-index: 1000;
        }

        .secondary-nav .btn-outline-secondary {
            border-color: #7D3C98;
            color: #7D3C98;
        }

        .secondary-nav .btn-outline-secondary:hover {
            background-color: #7D3C98;
            color: #fff;
        }

        .secondary-nav .navbar-text {
            font-size: 1rem;
            color: #333;
        }

        .btn-outline-secondary {
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-outline-secondary:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'admin_nav.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm secondary-nav">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>This page is static data only; it is under development.</h1>
                <div class="ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-1"></i> Admin: <?php echo $_SESSION['username']; ?>
                    </span>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h2 class="dashboard-title"><i class="fas fa-home me-2"></i> Admin Dashboard</h2>

                <div class="row g-3">
                    <div class="col-lg-4 col-md-6">
                        <div class="card text-center p-3">
                            <div class="card-body">
                                <h5><i class="fas fa-users text-white"></i> Total Users</h5>
                                <h3>200</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card text-center p-3">
                            <div class="card-body">
                                <h5><i class="fas fa-box text-white"></i> Total Products</h5>
                                <h3>150</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="card text-center p-3">
                            <div class="card-body">
                                <h5><i class="fas fa-chart-line text-white"></i> Total Sales</h5>
                                <h3>₱50,000</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="chart-container">
                            <h4 class="text-center"><i class="fas fa-chart-bar me-2"></i> Users Growth</h4>
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="chart-container">
                            <h4 class="text-center"><i class="fas fa-chart-bar me-2"></i> Sales Performance</h4>
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="chart-container">
                            <h4 class="text-center"><i class="fas fa-chart-bar me-2"></i> Orders Overview</h4>
                            <canvas id="ordersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="text-center mt-4">
                <p>&copy; 2025 Simple Shop Inventory. All Rights Reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("sidebarCollapse").addEventListener("click", function () {
                document.getElementById("sidebar").classList.toggle("active");
                document.getElementById("content").classList.toggle("active");
            });

            // Users Chart
            var ctxUsers = document.getElementById('usersChart').getContext('2d');
            var usersChart = new Chart(ctxUsers, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Total Users',
                        data: [120, 150, 180, 250, 300, 350],
                        backgroundColor: 'rgba(125, 60, 152, 0.2)',
                        borderColor: '#7D3C98',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Sales Chart
            var ctxSales = document.getElementById('salesChart').getContext('2d');
            var salesChart = new Chart(ctxSales, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Total Sales (₱)',
                        data: [10000, 15000, 20000, 25000, 30000, 40000],
                        backgroundColor: '#F4D03F',
                        borderColor: '#F39C12',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            // Orders Chart
            var ctxOrders = document.getElementById('ordersChart').getContext('2d');
            var ordersChart = new Chart(ctxOrders, {
                type: 'pie',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Total Orders',
                        data: [50, 60, 90, 120, 150, 180],
                        backgroundColor: ['#7D3C98', '#F4D03F', '#5DADE2', '#E74C3C', '#28B463', '#F39C12']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>

</html>
