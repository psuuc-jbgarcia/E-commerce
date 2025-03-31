<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}

require '../connection.php';

$sql = "SELECT order_id, tracking_code, username, product_names, quantities, grand_total, order_status FROM orders ORDER BY order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Golden Mart Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .status-dropdown { width: 150px; }
        .table th {
            background-color: #FFD700;
            color: black;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.php'; ?>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm secondary-nav">
            <h2 class="dashboard-title"><i class="fas fa-shopping-cart me-2"></i> Orders</h2>

                <div class="ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-1"></i> Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                </div>
            </nav>
            <div class="container-fluid mt-4">
                <div class="table-responsive">
                    <table id="ordersTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Tracking Code</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Quantities</th>
                                <th>Total Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['tracking_code']) ?></td>
                                    <td><?= htmlspecialchars($row['username']) ?></td>
                                    <td><?= htmlspecialchars($row['product_names']) ?></td>
                                    <td><?= htmlspecialchars($row['quantities']) ?></td>
                                    <td>₱<?= number_format($row['grand_total'], 2) ?></td>
                                    <td>
                                        <select class="form-select status-dropdown" data-id="<?= $row['order_id'] ?>">
                                            <option value="Pending" <?= $row['order_status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="To Receive" <?= $row['order_status'] == 'To Receive' ? 'selected' : '' ?>>To Receive</option>
                                            <option value="Delivered" <?= $row['order_status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#ordersTable').DataTable();
            $('.status-dropdown').on('change', function () {
                let orderId = $(this).data('id');
                let newStatus = $(this).val();
                Swal.fire({
                    title: "Are you sure?",
                    text: "You are about to update the order status.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, update it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "update_order_status.php",
                            type: "POST",
                            data: { order_id: orderId, order_status: newStatus },
                            success: function () {
                                Swal.fire({
                                    title: "Updated!",
                                    text: "Order status has been updated.",
                                    icon: "success",
                                    confirmButtonText: "OK"
                                });
                            },
                            error: function () {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Failed to update order status.",
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        });
                    } else {
                        location.reload();
                    }
                });
            });
        });
    </script>
</body>
</html>