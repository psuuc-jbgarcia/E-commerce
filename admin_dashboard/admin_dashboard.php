<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}
require '../connection.php';
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
            background: linear-gradient(135deg, #7D3C98, #5B2C6F);
            color: #fff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0px 12px 24px rgba(0, 0, 0, 0.15);
        }

        .dashboard-title {
            font-size: 2rem;
            font-weight: bold;
            color: #5B2C6F;
            margin-bottom: 30px;
        }

        .table-responsive {
            margin-top: 30px;
        }

        .table th {
            background-color: #7D3C98;
            color: #fff;
        }

        footer {
            background-color: #f8f9fa;
            padding: 12px 0;
            font-size: 0.9rem;
        }

        .navbar {
            background-color: #fff;
            border-bottom: 1px solid #dee2e6;
        }

        .navbar-text {
            font-size: 1rem;
            color: #5B2C6F;
        }

        .btn-outline-secondary {
            border-color: #5B2C6F;
            color: #5B2C6F;
        }

        .btn-outline-secondary:hover {
            background-color: #5B2C6F;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.php'; ?>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light shadow-sm">
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
                <h2 class="dashboard-title"><i class="fas fa-home me-2"></i> Admin Dashboard</h2>

                <div class="row g-4">
                    <!-- Total Users -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card text-center p-4">
                            <div class="card-body">
                                <h5><i class="fas fa-users text-white me-1"></i> Total Users</h5>
                                <?php
                                $user_query = mysqli_query($conn, "SELECT COUNT(id) AS total_users FROM users");
                                $user_count = mysqli_fetch_assoc($user_query);
                                ?>
                                <h3 class="mt-3"><?php echo $user_count['total_users']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Products -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card text-center p-4">
                            <div class="card-body">
                                <h5><i class="fas fa-box text-white me-1"></i> Total Products</h5>
                                <?php
                                $product_query = mysqli_query($conn, "SELECT COUNT(id) AS total_products FROM products");
                                $product_count = mysqli_fetch_assoc($product_query);
                                ?>
                                <h3 class="mt-3"><?php echo $product_count['total_products']; ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Sales -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card text-center p-4">
                            <div class="card-body">
                                <h5><i class="fas fa-chart-line text-white me-1"></i> Total Sales</h5>
                                <?php
                                $sales_query = mysqli_query($conn, "SELECT SUM(grand_total) AS total_sales FROM orders");
                                $sales_data = mysqli_fetch_assoc($sales_query);
                                ?>
                                <h3 class="mt-3">₱<?php echo number_format($sales_data['total_sales'], 2); ?></h3>
                            </div>
                        </div>
                    </div>

                    <!-- Total Orders -->
                    <div class="col-lg-3 col-md-6">
                        <div class="card text-center p-4">
                            <div class="card-body">
                                <h5><i class="fas fa-shopping-cart text-white me-1"></i> Total Orders</h5>
                                <?php
                                $order_query = mysqli_query($conn, "SELECT COUNT(order_id) AS total_orders FROM orders");
                                $order_count = mysqli_fetch_assoc($order_query);
                                ?>
                                <h3 class="mt-3"><?php echo $order_count['total_orders']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Table -->
                <div class="table-responsive mt-5">
                    <h4 class="text-center mb-4"><i class="fas fa-receipt me-2"></i> Recent Sales</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price (₱)</th>
                                <th>Total (₱)</th>
                                <th>Sale Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sales_data = mysqli_query($conn, "SELECT product_name, quantity, price, total, sale_date FROM sales ORDER BY sale_date DESC");
                            while ($sale = mysqli_fetch_assoc($sales_data)) {
                                echo "<tr>
                                    <td>{$sale['product_name']}</td>
                                    <td>{$sale['quantity']}</td>
                                    <td>₱" . number_format($sale['price'], 2) . "</td>
                                    <td>₱" . number_format($sale['total'], 2) . "</td>
                                    <td>{$sale['sale_date']}</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
