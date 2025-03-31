<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}

require '../connection.php';

$sql = "SELECT id, product_name, quantity, price, total, sale_date FROM sales ORDER BY sale_date DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sale History - Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/admin.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background-color: white;
            color: black;
        }

        .secondary-nav {
            background-color: white!important;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            z-index: 1000;
        }

        .secondary-nav .btn-outline-secondary {
            border-color: black;
            color: black;
        }

        .secondary-nav .btn-outline-secondary:hover {
            background-color: white;
            color: #7D3C98;
        }

        .table th {
            background-color: #FFD700;
            color: black;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.php'; ?>

        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-dark shadow-sm secondary-nav">
            <h2 class="mb-4"><i class="fas fa-history me-2"></i> Sale History</h2>

                <div class="ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-1"></i> Admin: <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                </div>
            </nav>

            <div class="container mt-4">

                <div class="table-responsive">
                    <table id="saleHistoryTable" class="table table-striped table-bordered">
                    <thead>
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Quantity</th>
        <th>Price (₱)</th>
        <th>Total (₱)</th>
        <th>Sale Date</th>
        <th>Actions</th> <!-- Make sure this matches the number of <td> below -->
    </tr>
</thead>
<tbody>
    <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= htmlspecialchars($row['id']) ?></td>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= htmlspecialchars($row['quantity']) ?></td>
                <td>₱<?= number_format($row['price'], 2) ?></td>
                <td>₱<?= number_format($row['total'], 2) ?></td>
                <td><?= date('Y-m-d h:i A', strtotime($row['sale_date'])) ?></td>
                <td>
                    <button class="btn btn-warning btn-sm edit-btn" 
                        data-id="<?= $row['id'] ?>" 
                        data-name="<?= htmlspecialchars($row['product_name']) ?>" 
                        data-quantity="<?= $row['quantity'] ?>" 
                        data-price="<?= $row['price'] ?>">Edit</button>
                    <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">Delete</button>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else : ?>
    
    <?php endif; ?>
</tbody>


                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php $conn->close(); ?>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
$(document).ready(function () {
    $('#saleHistoryTable').DataTable({
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 10,
        ordering: true,
        order: [[5, 'desc']],
        language: {
            search: '<i class="fas fa-search me-1"></i> Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            infoEmpty: 'No records available',
            zeroRecords: 'No matching records found'
        }
    });

    // DELETE FUNCTION
    $('.delete-btn').on('click', function () {
        let saleId = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "delete_sales.php?id=" + saleId;
            }
        });
    });

    // EDIT FUNCTION
    $('.edit-btn').on('click', function () {
        let saleId = $(this).data('id');
        let productName = $(this).data('name');
        let quantity = $(this).data('quantity');
        let price = $(this).data('price');

        Swal.fire({
            title: "Edit Sale Record",
            html: `
                <input type="text" id="productName" class="swal2-input" placeholder="Product Name" value="${productName}">
                <input type="number" id="quantity" class="swal2-input" placeholder="Quantity" value="${quantity}">
                <input type="number" id="price" class="swal2-input" placeholder="Price" value="${price}">
            `,
            showCancelButton: true,
            confirmButtonText: "Save Changes",
            preConfirm: () => {
                let updatedProductName = document.getElementById('productName').value;
                let updatedQuantity = document.getElementById('quantity').value;
                let updatedPrice = document.getElementById('price').value;

                if (!updatedProductName || updatedQuantity <= 0 || updatedPrice <= 0) {
                    Swal.showValidationMessage("Please enter valid values!");
                    return false;
                }

                return {
                    id: saleId,
                    productName: updatedProductName,
                    quantity: updatedQuantity,
                    price: updatedPrice
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let data = result.value;

                $.ajax({
                    url: "edit_sales.php",
                    type: "POST",
                    data: {
                        id: data.id,
                        product_name: data.productName,
                        quantity: data.quantity,
                        price: data.price
                    },
                    success: function(response) {
                        Swal.fire({
                            title: "Updated!",
                            text: "Sale record has been updated successfully.",
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to update sale record.",
                            icon: "error",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }
        });
    });
});


    </script>
</body>
</html>
