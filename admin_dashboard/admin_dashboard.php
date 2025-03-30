<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Golden Mart Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f4f4f4;
        }

        .dashboard-container {
            margin-top: 50px;
        }

        .card {
            transition: 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .logout-btn {
            background-color: #c0392b;
            color: #fff;
        }

        .logout-btn:hover {
            background-color: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="container dashboard-container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
            <a href="logout.php" class="btn logout-btn"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm" onclick="goToPage('manage_users.php')">
                    <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                    <h5>Manage Users</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm" onclick="goToPage('inventory.php')">
                    <i class="fas fa-boxes fa-3x mb-3 text-success"></i>
                    <h5>Inventory Management</h5>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center p-4 shadow-sm" onclick="goToPage('sales_report.php')">
                    <i class="fas fa-chart-line fa-3x mb-3 text-warning"></i>
                    <h5>Sales Reports</h5>
                </div>
            </div>
        </div>
    </div>

    <script>
        function goToPage(page) {
            window.location.href = page;
        }
    </script>
</body>

</html>
