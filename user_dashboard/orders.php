<?php
session_start();
require '../connection.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Small Shop Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center mb-4"><i class="fas fa-box me-1"></i> Your Orders</h2>
        <table class="table table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Order ID</th>
                    <th>Status</th>
                    <th>Total (₱)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1001</td>
                    <td><span class="badge bg-warning">Processing</span></td>
                    <td>₱350.00</td>
                </tr>
                <tr>
                    <td>1002</td>
                    <td><span class="badge bg-success">Delivered</span></td>
                    <td>₱150.00</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</html>
