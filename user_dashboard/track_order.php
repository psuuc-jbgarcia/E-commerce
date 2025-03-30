<?php 
session_start();
require '../connection.php';

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

    .floating-icons {
        position: fixed;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        display: flex;
        gap: 3rem;
        width: 90%;
        max-width: 600px;
        justify-content: center;
        z-index: 999;
    }

    .floating-icons div {
        text-align: center;
        flex: 1;
    }

    .order-status-icon {
        font-size: 1.8rem;
        color: #7D3C98;
        margin-bottom: 0.5rem;
        transition: color 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .inactive-icon {
        opacity: 0.5;
    }

    .active-icon {
        color: #F4D03F;
        opacity: 1;
        box-shadow: 0 0 15px #F4D03F, 0 0 30px #F4D03F;
        border-radius: 50%;
    }

    .floating-icons div p {
        font-size: 0.9rem;
        margin: 0;
        color: #333;
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
    </div>
</div>

<!-- Cancelled Orders Section -->
<div class="div2">
    <div class="cancelled-container">
        <p class="cancelled-title"><i class="fa-solid fa-ban me-2"></i>Cancelled Orders</p>
        <?php if (isset($orders['Cancelled'])): ?>
            <?php foreach ($orders['Cancelled'] as $order): ?>
                <div class="cancelled-card">
                    <strong>Order #<?php echo $order['order_id']; ?></strong>
                    <p>Tracking Code: <?php echo $order['tracking_code']; ?></p>
                    <p><strong>Products:</strong> <?php echo $order['product_names']; ?></p>
                    <p><strong>Grand Total:</strong> ₱<?php echo number_format($order['grand_total'], 2); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No cancelled orders.</p>
        <?php endif; ?>
    </div>
</div>
<!-- Status Icons Section -->
<div class="div3 floating-icons">
    <div>
        <i class="fa-solid fa-clock order-status-icon inactive-icon" id="pending-icon"></i>
        <p>Pending</p>
    </div>
    <div>
        <i class="fa-solid fa-truck order-status-icon inactive-icon" id="received-icon"></i>
        <p>To Receive</p>
    </div>
    <div>
        <i class="fa-solid fa-box-open order-status-icon inactive-icon" id="delivered-icon"></i>
        <p>Delivered</p>
    </div>
</div>

       
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tabs = document.querySelectorAll(".nav-link");
        const icons = {
            "pending-tab": "pending-icon",
            "received-tab": "received-icon",
            "delivered-tab": "delivered-icon"
        };

        // Function to update icon styles
        function updateIcons(activeTab) {
            Object.keys(icons).forEach(tab => {
                const icon = document.getElementById(icons[tab]);
                if (tab === activeTab) {
                    icon.classList.add("active-icon");
                    icon.classList.remove("inactive-icon");
                } else {
                    icon.classList.add("inactive-icon");
                    icon.classList.remove("active-icon");
                }
            });
        }

        // Set the default active icon to Pending
        updateIcons("pending-tab");

        // Attach event listeners to tabs
        tabs.forEach(tab => {
            tab.addEventListener("click", function () {
                updateIcons(this.id);
            });
        });
    });
    document.getElementById('pending-icon').classList.add('active-icon');

</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
