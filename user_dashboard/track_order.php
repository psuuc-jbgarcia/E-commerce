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

    .div3 {
    grid-column: span 3;
    grid-row-start: 5;
    display: flex;
    justify-content: space-around;
    background-color:  #7D3C98 ; 
    border: 3px solid #d4af37; 
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0px 4px 8px rgba(218, 165, 32, 0.5);
    margin-bottom: 70px;
}


    .order-status-icon {
        font-size: 36px;
        transition: all 0.3s ease-in-out;
    }

    .inactive-icon {
        opacity: 0.5;
    }

    .active-icon {
        color: #ffc107;
        text-shadow: 0 0 15px #ffc107;
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

    .div3 div {
        text-align: center;
        padding: 10px;
    }

    .div3 p {
        margin-top: 5px;
        font-weight: bold;
    }

    </style>
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container parent">

        <!-- Tabs Section -->
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

            <div class="tab-content">
                <!-- Pending Orders -->
                <div class="tab-pane fade show active" id="pending">
                    <?php if (isset($orders['Pending'])): ?>
                        <?php foreach ($orders['Pending'] as $order): ?>
                            <div class="order-card">
                                <strong>Order #<?php echo $order['order_id']; ?></strong>
                                <p>Tracking Code: <?php echo $order['tracking_code']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No pending orders.</p>
                    <?php endif; ?>
                </div>

                <!-- To Receive Orders -->
                <div class="tab-pane fade" id="received">
                    <?php if (isset($orders['To Receive'])): ?>
                        <?php foreach ($orders['To Receive'] as $order): ?>
                            <div class="order-card">
                                <strong>Order #<?php echo $order['order_id']; ?></strong>
                                <p>Tracking Code: <?php echo $order['tracking_code']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No orders to receive.</p>
                    <?php endif; ?>
                </div>

                <!-- Delivered Orders -->
                <div class="tab-pane fade" id="delivered">
                    <?php if (isset($orders['Delivered'])): ?>
                        <?php foreach ($orders['Delivered'] as $order): ?>
                            <div class="order-card">
                                <strong>Order #<?php echo $order['order_id']; ?></strong>
                                <p>Tracking Code: <?php echo $order['tracking_code']; ?></p>
                            </div>
                        <?php endforeach; ?>
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
        <div class="div3">
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
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
