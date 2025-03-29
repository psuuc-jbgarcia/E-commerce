<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];

$sql = "
    SELECT order_id, tracking_code, product_names, order_status, grand_total, order_date, shipping_address,quantities, payment_method,shipping_address
    FROM orders 
    WHERE username = ? 
    ORDER BY order_date DESC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
    $order_id = intval($_POST['order_id']);
    $cancel_stmt = $conn->prepare("UPDATE orders SET order_status = 'Cancelled' WHERE order_id = ? AND username = ? AND order_status = 'Pending'");
    $cancel_stmt->bind_param("is", $order_id, $email);
    if ($cancel_stmt->execute()) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Success',
                    text: 'Order has been cancelled successfully!',
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <style>
        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #F4D03F;
            color: #333333;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            margin-bottom: 5px;
        }

        .card .btn {
            width: 100%;
            margin-top: 10px;
        }

        .card .btn-cancel {
            background-color: #E74C3C;
            color: #FFFFFF;
        }

        .card .btn-cancel:hover {
            background-color: #C0392B;
        }

        .card .btn-view {
            background-color: #F4D03F;
            color: #333333;
        }

        .card .btn-view:hover {
            background-color: #F1C40F;
        }

        .badge {
            padding: 5px 10px;
            font-size: 0.9rem;
        }

        .badge-warning {
            background-color: #F4D03F;
            color: #333333;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-secondary {
            background-color: #6c757d;
        }

        .badge-danger {
            background-color: #E74C3C;
        }

        /* DataTable styling */
        table.dataTable {
            width: 100% !important;
            margin-top: 20px;
            background-color: #ffffff;
            border-radius: 8px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 5px;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 5px;
            padding: 5px;
            border: 1px solid #ddd;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 5px;
            padding: 5px 10px;
        }

        /* Add distinct color to the headers */
        th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: bold;
        }

        /* Add alternating row colors */
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #f4d03f;
            border: 1px solid #f4d03f;
            color: white;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #f4d03f;
            color: white;
        }
    </style>
</head>
<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <?php if ($result->num_rows > 0): ?>
            <table id="ordersTable" class="display">
                <thead>
                    <tr>
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
        <button type="submit" name="cancel_order" class="btn btn-danger btn-sm" style="background-color: #E74C3C; color: white; border-color: #E74C3C;">
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
                    // Exploding the product names and quantities
                    $product_names = explode(',', $row['product_names']);
                    $quantities = explode(',', $row['quantities']);
                    
                    // Loop through and display each item with its quantity
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            $('#ordersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "responsive": true
            });
        });
    </script>
    <script>
    function showModal(event) {
        event.preventDefault(); // Prevent the form from submitting
        // You can add additional code if needed to handle specific logic before showing the modal
    }
</script>

</body>
</html>
