<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$email = $_SESSION['email'];

$sql = "
    SELECT order_id, tracking_code, product_ids, product_names, quantities, order_status, grand_total, order_date, shipping_address, payment_method
    FROM orders 
    WHERE username = ? 
    ORDER BY order_date DESC, order_id DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $order_id = intval($_POST['order_id']);

    $order_query = $conn->prepare("SELECT product_ids, quantities FROM orders WHERE order_id = ? AND username = ? AND order_status = 'Pending'");
    $order_query->bind_param("is", $order_id, $email);
    $order_query->execute();
    $order_result = $order_query->get_result();

    if ($order_result->num_rows > 0) {
        $order_data = $order_result->fetch_assoc();
        $product_ids = explode(",", $order_data['product_ids']);
        $quantities = explode(",", $order_data['quantities']);

        $cancel_stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ? AND username = ? AND order_status = 'Pending'");
        $cancel_stmt->bind_param("is", $order_id, $email);

        if ($cancel_stmt->execute()) {
            foreach ($product_ids as $index => $product_id) {
                $product_id = intval(trim($product_id)); 
                $qty_to_restock = intval(trim($quantities[$index]));

                $update_stock_stmt = $conn->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                $update_stock_stmt->bind_param("ii", $qty_to_restock, $product_id);
                $update_stock_stmt->execute();
            }

            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Success',
                        text: 'Order has been cancelled and stock has been restored!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'orders.php';
                    });
                });
            </script>";
            exit();
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Error',
                        text: 'Unable to cancel order.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Order cannot be cancelled or it has already been processed.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders - Small Shop Inventory</title>
    <link rel="stylesheet" href="../static/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        th {
            background-color: #7D3C98 !important;
            color: #ffffff !important;
            text-align: center;
        }

        tbody tr:hover {
            background-color: #f4f4f4 !important;
        }

        table {
            width: 100%;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .badge {
            font-size: 0.9rem;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .badge-warning {
            background-color: #F4D03F;
            color: #333333;
        }

        .badge-success {
            background-color: #28a745;
            color: #fff;
        }

        .badge-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        .badge-danger {
            background-color: #E74C3C;
            color: #fff;
        }

        .btn-cancel {
    background-color: #e74c3c;
    color: white;
    border: none;
    padding: 5px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
}

.btn-cancel:hover {
    background-color: #c0392b; 
}

.btn-cancel1 {
    background-color: #e74c3c !important;
    border: none !important;
    padding: 5px 15px !important;
    border-radius: 5px !important;
    cursor: pointer !important;
    font-size: 14px !important;
    transition: background-color 0.3s ease !important;
}

.btn-cancel1:hover {
    background-color: #c0392b !important;
}

        .extra-info {
            background-color: #ffffff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th style="display: none;">Order ID</th>
                        <th>Tracking No</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td style="display: none;"><?php echo $row['order_id']; ?></td>
                            <td><?php echo $row['tracking_code']; ?></td>
                            <td><?php echo $row['product_names']; ?></td>
                            <td>₱<?php echo number_format($row['grand_total'], 2); ?></td>
                            <td>
                                <?php
                                $status = $row['order_status'];
                                $badgeClass = match ($status) {
                                    'Delivered' => 'badge-success',
                                    'Processing' => 'badge-warning',
                                    'Cancelled' => 'badge-danger',
                                    default => 'badge-secondary'
                                };
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                            </td>
                            <td>
                                <form method="POST" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button class="btn btn-view btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo $row['order_id']; ?>" onclick="showModal(event)">
                                        <i class="fas fa-eye me-1"></i> View Details
                                    </button>
                                </form>

                                <?php if ($status === 'Pending'): ?>
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                        <button type="submit" name="cancel_order" class="btn btn-cancel1 btn-sm">
    <i class="fas fa-times-circle me-1"></i> Cancel Order
</button>

                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <!-- Modal for order details -->
                        <div class="modal fade" id="orderModal<?php echo $row['order_id']; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo $row['order_id']; ?>" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-warning">
                                        <h5 class="modal-title" id="orderModalLabel<?php echo $row['order_id']; ?>"><i class="fas fa-box"></i> Order Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Tracking Number:</strong> <?php echo $row['tracking_code']; ?></p>
                                        <p><strong>Items:</strong> 
                                            <?php
                                            $product_names = explode(',', $row['product_names']);
                                            $quantities = explode(',', $row['quantities']);
                                            
                                            for ($i = 0; $i < count($product_names); $i++) {
                                                echo $product_names[$i] . ' - Quantity: ' . $quantities[$i] . '<br>';
                                            }
                                            ?>
                                        </p>
                                        <p><strong>Total:</strong> ₱<?php echo number_format($row['grand_total'], 2); ?></p>
                                        <p><strong>Order Date:</strong> <?php echo date('F j, Y', strtotime($row['order_date'])); ?></p>
                                        <p><strong>Shipping Address:</strong> <?php echo $row['shipping_address']; ?></p>
                                        <p><strong>Payment Method:</strong> <?php echo $row['payment_method']; ?></p>
                                        <p><strong>Status:</strong> 
                                            <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                        </p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info text-center">No orders found.</div>
        <?php endif; ?>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

</body>
</html>
