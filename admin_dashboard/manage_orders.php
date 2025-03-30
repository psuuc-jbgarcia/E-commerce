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
    <title>Manage Orders - Golden Mart Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/admin.css">
    <style>
        #content {
            transition: all 0.3s;
            width: 100%;
            padding: 20px;
        }

        .table thead th {
            background-color: #7D3C98;
            color: #fff;
        }

        .btn-view,
        .btn-edit,
        .btn-delete {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .btn-view {
            background-color: #5DADE2;
            color: #fff;
        }

        .btn-edit {
            background-color: #F4D03F;
            color: #333;
        }

        .btn-delete {
            background-color: #E74C3C;
            color: #fff;
        }

        .btn-view:hover,
        .btn-edit:hover,
        .btn-delete:hover {
            opacity: 0.9;
        }

        .dashboard-title {
            font-size: 1.8rem;
            font-weight: bold;
            color: #5B2C6F;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php include 'admin_nav.php'; ?>

        <!-- Page Content -->
        <div id="content">
            <!-- Secondary Nav -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm secondary-nav">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-1"></i> Admin: <?php echo $_SESSION['username']; ?>
                    </span>
                </div>
            </nav>

            <div class="container-fluid mt-4">
                <h2 class="dashboard-title"><i class="fas fa-shopping-cart me-2"></i> Manage Orders</h2>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Order Tracker Code</th>
                                <th>Customer Name</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Static Data for Orders -->
                            <tr>
                                <td>#001</td>
                                <td>Juan Dela Cruz</td>
                                <td>Rice Cooker</td>
                                <td>2</td>
                                <td>₱1,500</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <button class="btn btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#002</td>
                                <td>Maria Clara</td>
                                <td>Electric Fan</td>
                                <td>1</td>
                                <td>₱900</td>
                                <td><span class="badge bg-warning text-dark">Pending</span></td>
                                <td>
                                    <button class="btn btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#003</td>
                                <td>Pedro Penduko</td>
                                <td>Blender</td>
                                <td>1</td>
                                <td>₱1,200</td>
                                <td><span class="badge bg-danger">Cancelled</span></td>
                                <td>
                                    <button class="btn btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>#004</td>
                                <td>Juan Tamad</td>
                                <td>Gas Stove</td>
                                <td>1</td>
                                <td>₱1,800</td>
                                <td><span class="badge bg-info">Processing</span></td>
                                <td>
                                    <button class="btn btn-view"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-edit"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-delete"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <footer class="text-center mt-4">
                <p>&copy; 2025 Golden Mart Inventory. All Rights Reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.getElementById("sidebarCollapse").addEventListener("click", function () {
                document.getElementById("sidebar").classList.toggle("active");
                document.getElementById("content").classList.toggle("active");
            });
        });
    </script>
</body>

</html>
