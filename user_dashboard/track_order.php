<?php
session_start();
require '../connection.php';

// Fetch all orders and group them by status
$query = "SELECT order_id, tracking_code, username, shipping_address, contact_number, product_ids, product_names, quantities, payment_method, shipping_fee_total, grand_total, order_status, order_date FROM orders ORDER BY order_status";
$result = $conn->query($query);
$orders = [];

while ($row = $result->fetch_assoc()) {
    $orders[$row['order_status']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Orders - Small Shop Inventory</title>
    <link rel="stylesheet" href="../static/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
    /* Updated Tab Styling for Better Visibility */
    .nav-tabs .nav-link {
        background-color: #343a40;
        color: #fff;
        border: none;
    }

    .nav-tabs .nav-link.active {
        background-color: #ffc107;
        color: #000;
        font-weight: bold;
    }

    .nav-tabs .nav-link:hover {
        background-color: #495057;
        color: #fff;
    }

    /* Golden Yellow Card Styling */
    .order-card {
        margin-bottom: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: none;
        background-color: #ffd700; /* Golden Yellow */
    }

    .order-card-header {
        background-color: #ffcc00;
        padding: 15px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        font-weight: bold;
        font-size: 1.2rem;
        color: #000;
    }

    .order-card-body p {
        color: #000;
    }

    /* Order Tracking UI */
    .tracking-container {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        text-align: center;
        margin-top: 20px;
    }

    .tracking-header {
        font-size: 1.5rem;
        font-weight: bold;
        color: #343a40;
    }

    .tracking-progress {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        padding: 20px 0;
    }

    .tracking-progress .step {
        position: relative;
        width: 100%;
        text-align: center;
    }

    .tracking-progress .step .icon {
        background: #6c5ce7;
        color: #fff;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border-radius: 50%;
        display: inline-block;
    }

    .tracking-progress .line {
        position: absolute;
        top: 50%;
        left: 0;
        width: 100%;
        height: 5px;
        background: #6c5ce7;
        z-index: -1;
    }

    .tracking-status {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    .tracking-status div {
        text-align: center;
    }
</style>

</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">

        <!-- Tab Navigation -->
        <ul class="nav nav-tabs" id="orderStatusTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="true">Pending</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="received-tab" data-bs-toggle="tab" href="#received" role="tab" aria-controls="received" aria-selected="false">To Receive</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="delivered-tab" data-bs-toggle="tab" href="#delivered" role="tab" aria-controls="delivered" aria-selected="false">Delivered</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</a>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="orderStatusTabContent">
            <!-- Pending Orders -->
            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                <?php if (isset($orders['Pending'])): ?>
                    <div class="row mt-4">
                        <?php foreach ($orders['Pending'] as $order): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card order-card">
                                    <div class="order-card-header">
                                        Order #<?php echo $order['order_id']; ?>
                                    </div>
                                    <div class="order-card-body">
                                        <p><strong>Tracking Code:</strong> <?php echo $order['tracking_code']; ?></p>
                                        <p><strong>Products:</strong> <?php echo $order['product_names']; ?></p>
                                        <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                        <p><strong>Shipping Fee:</strong> $<?php echo number_format($order['shipping_fee_total'], 2); ?></p>
                                        <p><strong>Grand Total:</strong> $<?php echo number_format($order['grand_total'], 2); ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No pending orders.</p>
                <?php endif; ?>
            </div>

            <!-- To Receive Orders -->
            <div class="tab-pane fade" id="received" role="tabpanel" aria-labelledby="received-tab">
                <?php if (isset($orders['To Receive'])): ?>
                    <div class="row mt-4">
                        <?php foreach ($orders['To Receive'] as $order): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card order-card">
                                    <div class="order-card-header">
                                        Order #<?php echo $order['order_id']; ?>
                                    </div>
                                    <div class="order-card-body">
                                        <p><strong>Tracking Code:</strong> <?php echo $order['tracking_code']; ?></p>
                                        <p><strong>Products:</strong> <?php echo $order['product_names']; ?></p>
                                        <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                        <p><strong>Shipping Fee:</strong> $<?php echo number_format($order['shipping_fee_total'], 2); ?></p>
                                        <p><strong>Grand Total:</strong> $<?php echo number_format($order['grand_total'], 2); ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No orders to receive.</p>
                <?php endif; ?>
            </div>

            <!-- Delivered Orders -->
            <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                <?php if (isset($orders['Delivered'])): ?>
                    <div class="row mt-4">
                        <?php foreach ($orders['Delivered'] as $order): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card order-card">
                                    <div class="order-card-header">
                                        Order #<?php echo $order['order_id']; ?>
                                    </div>
                                    <div class="order-card-body">
                                        <p><strong>Tracking Code:</strong> <?php echo $order['tracking_code']; ?></p>
                                        <p><strong>Products:</strong> <?php echo $order['product_names']; ?></p>
                                        <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                        <p><strong>Shipping Fee:</strong> $<?php echo number_format($order['shipping_fee_total'], 2); ?></p>
                                        <p><strong>Grand Total:</strong> $<?php echo number_format($order['grand_total'], 2); ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No delivered orders.</p>
                <?php endif; ?>
            </div>

            <!-- Cancelled Orders -->
            <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                <?php if (isset($orders['Cancelled'])): ?>
                    <div class="row mt-4">
                        <?php foreach ($orders['Cancelled'] as $order): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card order-card">
                                    <div class="order-card-header">
                                        Order #<?php echo $order['order_id']; ?>
                                    </div>
                                    <div class="order-card-body">
                                        <p><strong>Tracking Code:</strong> <?php echo $order['tracking_code']; ?></p>
                                        <p><strong>Products:</strong> <?php echo $order['product_names']; ?></p>
                                        <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                        <p><strong>Shipping Fee:</strong> $<?php echo number_format($order['shipping_fee_total'], 2); ?></p>
                                        <p><strong>Grand Total:</strong> $<?php echo number_format($order['grand_total'], 2); ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($order['order_date'])); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>No cancelled orders.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
