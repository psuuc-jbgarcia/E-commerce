<?php 
session_start();
require '../connection.php';
if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$username = $_SESSION['email'];

$query = "SELECT order_id, tracking_code, username, shipping_address, contact_number, product_ids, product_names, quantities, payment_method, shipping_fee_total, grand_total, order_status, order_date 
          FROM orders 
          WHERE username = '$username' 
          ORDER BY order_status";

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .parent {
            display: grid;
            grid-template-columns: 2.5fr 1.5fr;
            grid-template-rows: auto auto;
            gap: 20px;
            margin-top: 20px;
        }

        .div1 {
            grid-column: span 1;
            grid-row: span 2;
            width: 100%;
        }

        .div2 {
            grid-column: span 1;
            margin-top: 0;
        }

        .order-card {
            background-color: #fff;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-left: 5px solid #ffc107;
            transition: all 0.3s ease-in-out;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .accordion-item {
            border: 1px solid #ddd;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .accordion-button {
            background-color: #f8f9fa;
            color: #343a40;
            font-weight: bold;
        }

        .accordion-button:focus {
            box-shadow: none;
        }

        .accordion-body {
            background-color: #f1f1f1;
        }

        .accordion-button.collapsed {
            color: #007bff;
        }

        .cancelled-container {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(255, 0, 0, 0.1);
        }

        .cancelled-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #721c24;
            margin-bottom: 10px;
        }

        .cancelled-card {
            background-color: #f8d7da;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #f5c6cb;
            margin-bottom: 10px;
        }

        .nav-tabs .nav-link {
            background-color: #343a40;
            color: #fff;
        }

        .nav-tabs .nav-link.active {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }

    </style>
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container parent">
        <div class="div1">
            <ul class="nav nav-tabs" id="orderStatusTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="received-tab" data-bs-toggle="tab" href="#received">To Receive</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="delivered-tab" data-bs-toggle="tab" href="#delivered">Delivered</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled">Cancelled</a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <!-- Pending Orders -->
                <div class="tab-pane fade show active" id="pending">
                    <?php if (isset($orders['Pending'])): ?>
                        <div class="accordion" id="pendingAccordion">
                            <?php foreach ($orders['Pending'] as $order): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingPending<?php echo $order['order_id']; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detailsPending<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="detailsPending<?php echo $order['order_id']; ?>">
                                            Tracking Code: <?php echo $order['tracking_code']; ?>
                                        </button>
                                    </h2>
                                    <div id="detailsPending<?php echo $order['order_id']; ?>" class="accordion-collapse collapse" aria-labelledby="headingPending<?php echo $order['order_id']; ?>" data-bs-parent="#pendingAccordion">
                                        <div class="accordion-body">
                                            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                                            <p><strong>Username:</strong> <?php echo $order['username']; ?></p>
                                            <p><strong>Product Names:</strong> <?php echo $order['product_names']; ?></p>
                                            <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                            <p><strong>Grand Total:</strong> ₱<?php echo $order['grand_total']; ?></p>
                                            <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
                                            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
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
                <div class="tab-pane fade" id="received">
                    <?php if (isset($orders['To Receive'])): ?>
                        <div class="accordion" id="receivedAccordion">
                            <?php foreach ($orders['To Receive'] as $order): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingReceived<?php echo $order['order_id']; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detailsReceived<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="detailsReceived<?php echo $order['order_id']; ?>">
                                            Tracking Code: <?php echo $order['tracking_code']; ?>
                                        </button>
                                    </h2>
                                    <div id="detailsReceived<?php echo $order['order_id']; ?>" class="accordion-collapse collapse" aria-labelledby="headingReceived<?php echo $order['order_id']; ?>" data-bs-parent="#receivedAccordion">
                                        <div class="accordion-body">
                                            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                                            <p><strong>Username:</strong> <?php echo $order['username']; ?></p>
                                            <p><strong>Product Names:</strong> <?php echo $order['product_names']; ?></p>
                                            <p><strong>Quantities:</strong> <?php echo $order['quantities']; ?></p>
                                            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                            <p><strong>Grand Total:</strong> ₱<?php echo $order['grand_total']; ?></p>
                                            <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
                                            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
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
                <div class="tab-pane fade" id="delivered">
                    <?php if (isset($orders['Delivered'])): ?>
                        <div class="accordion" id="deliveredAccordion">
                            <?php foreach ($orders['Delivered'] as $order): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingDelivered<?php echo $order['order_id']; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detailsDelivered<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="detailsDelivered<?php echo $order['order_id']; ?>">
                                            Tracking Code: <?php echo $order['tracking_code']; ?>
                                        </button>
                                    </h2>
                                    <div id="detailsDelivered<?php echo $order['order_id']; ?>" class="accordion-collapse collapse" aria-labelledby="headingDelivered<?php echo $order['order_id']; ?>" data-bs-parent="#deliveredAccordion">
                                        <div class="accordion-body">
                                            <p><strong>Product Names:</strong> <?php echo $order['product_names']; ?></p>
                                            <p><strong>Payment Method:</strong> <?php echo $order['payment_method']; ?></p>
                                            <p><strong>Grand Total:</strong> ₱<?php echo $order['grand_total']; ?></p>
                                            <p><strong>Status:</strong> <?php echo $order['order_status']; ?></p>
                                            <p><strong>Order Date:</strong> <?php echo $order['order_date']; ?></p>
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
                <div class="tab-pane fade" id="cancelled">
                    <?php if (isset($orders['Cancelled'])): ?>
                        <div class="accordion" id="cancelledAccordion">
                            <?php foreach ($orders['Cancelled'] as $order): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingCancelled<?php echo $order['order_id']; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#detailsCancelled<?php echo $order['order_id']; ?>" aria-expanded="false" aria-controls="detailsCancelled<?php echo $order['order_id']; ?>">
                                            Tracking Code: <?php echo $order['tracking_code']; ?>
                                        </button>
                                    </h2>
                                    <div id="detailsCancelled<?php echo $order['order_id']; ?>" class="accordion-collapse collapse" aria-labelledby="headingCancelled<?php echo $order['order_id']; ?>" data-bs-parent="#cancelledAccordion">
                                        <div class="accordion-body">
                                            <p><strong>Order ID:</strong> <?php echo $order['order_id']; ?></p>
                                            <p><strong>Product Names:</strong> <?php echo $order['product_names']; ?></p>
                                            <p><strong>Grand Total:</strong> ₱<?php echo number_format($order['grand_total'], 2); ?></p>
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
    </div>

</body>

</html>
