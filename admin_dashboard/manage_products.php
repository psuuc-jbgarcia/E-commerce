<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}

require '../connection.php';

$category_result = $conn->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
$categories = [];
while ($row = $category_result->fetch_assoc()) {
    $categories[] = $row['category'];
}

$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../static/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn-sm-custom {
            padding: 8px 15px;
            font-size: 14px;
            margin-right: 5px;
        }

        .modal-dialog {
            max-width: 900px;
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }

        .modal-footer {
            background-color: #f1f1f1;
        }

        .table th {
            background-color: #FFD700;
            color: black;
            font-weight: bold;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group-custom {
            margin-bottom: 15px;
        }

        .btn-group-custom .btn {
            margin-right: 8px;
        }

        .form-label {
            font-weight: bold;
        }

        #new_category {
            display: none;
        }

        .btn-success-custom {
            background-color: #28a745;
            color: white;
        }

        .btn-danger-custom {
            background-color: #dc3545;
            color: white;
        }

        .btn-info-custom {
            background-color: #17a2b8;
            color: white;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <?php include 'admin_nav.php'; ?>

        <div id="content">
            <div class="container mt-4">
                <h2 class="mb-4"><i class="fas fa-box me-2"></i> Manage Products</h2>

                <!-- Button Group -->
                <div class="btn-group-custom">
                    <button class="btn btn-primary btn-sm-custom" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="fas fa-plus me-1"></i> Add Product
                    </button>
                </div>

                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="productTable" width="100%">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Image</th>
                                <th>Stock</th>
                                <th>Category</th>
                                <th>Created At</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
                            while ($row = $result->fetch_assoc()) :
                            ?>
                                <tr>
                                    <!-- <td><?= $row['id'] ?></td> -->
                                    <td><?= $row['name'] ?></td>
                                    <td><?= $row['description'] ?></td>
                                    <td>₱<?= number_format($row['price'], 2) ?></td>
                                    <td>
                                    <img src="../uploads/<?= $row['image_name'] ?>" alt="<?= $row['name'] ?>" width="50" height="50" class="rounded">
                                    </td>
                                    <td><?= $row['stock'] ?></td>
                                    <td><?= $row['category'] ?></td>
                                    <td><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></td>
                                    <td>
                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm-custom edit-btn" data-id="<?= $row['id'] ?>" data-name="<?= $row['name'] ?>" data-description="<?= $row['description'] ?>" data-price="<?= $row['price'] ?>" data-stock="<?= $row['stock'] ?>" data-category="<?= $row['category'] ?>" data-image="<?= $row['image_name'] ?>">
    <i class="fas fa-edit"></i>
</a>

                                        <a href="delete_product.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm-custom delete-btn">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="addProductForm" action="add_product.php" method="POST" enctype="multipart/form-data" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="fas fa-box me-1"></i> Add Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Product Name</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price" class="form-label">Price</label>
                                <input type="number" step="0.01" name="price" id="price" class="form-control">
                            </div>
                        </div>
                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="stock" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category) : ?>
                                        <option value="<?= $category ?>"><?= $category ?></option>
                                    <?php endforeach; ?>
                                    <option value="new">Add New Category</option>
                                </select>
                            </div>
                            <div class="mb-3" id="new_category">
                                <label for="new_category_input" class="form-label">New Category Name</label>
                                <input type="text" name="new_category" id="new_category_input" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="product_image" class="form-label">Product Image</label>
                                <input type="file" name="product_image" id="product_image" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" id="saveProduct" class="btn btn-success btn-sm-custom">
    <i class="fas fa-check me-1"></i> Add Product
</button>
                </div>
            </form>
        </div>
    </div>
    <!-- edit -->
     <!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="editProductForm" action="update_product.php" method="POST" enctype="multipart/form-data" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel"><i class="fas fa-box me-1"></i> Edit Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea name="description" id="edit_description" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="edit_price" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="edit_stock" class="form-label">Stock</label>
                            <input type="number" name="stock" id="edit_stock" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category" class="form-label">Category</label>
                            <select name="category" id="edit_category" class="form-select">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category) : ?>
                                    <option value="<?= $category ?>"><?= $category ?></option>
                                <?php endforeach; ?>
                                <option value="new">Add New Category</option>
                            </select>
                        </div>
                        <div class="mb-3" id="edit_new_category">
                            <label for="edit_new_category_input" class="form-label">New Category Name</label>
                            <input type="text" name="new_category" id="edit_new_category_input" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="edit_product_image" class="form-label">Product Image</label>
                            <input type="file" name="product_image" id="edit_product_image" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="product_id" id="edit_product_id">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
        </form>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>

$(document).ready(function () {
    $('#productTable').DataTable({
        dom: '<"top"f>rt<"bottom"Bp><"clear">',
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                title: 'Product Report',
                className: 'btn btn-success-custom'
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fas fa-file-pdf me-1"></i> PDF',
                title: 'Product Report',
                className: 'btn btn-danger-custom',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print me-1"></i> Print',
                className: 'btn btn-info-custom',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ],
        responsive: true,
        autoWidth: false,
        pageLength: 10
    });

    $('#category').on('change', function () {
        if ($(this).val() === 'new') {
            $('#new_category').show();
        } else {
            $('#new_category').hide();
            $('#new_category_input').val(''); 
        }
    });
//////////////////////////manage prod function
//delete
    $(document).on('click', '.delete-btn', function (e) {
        e.preventDefault();
        const href = $(this).attr('href');

        Swal.fire({
            title: 'Are you sure?',
            text: 'This action cannot be undone!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
//add
    $('#saveProduct').click(function () {
        let isValid = true;
        let errorMessage = "";

        const name = $('#name').val().trim();
        const description = $('#description').val().trim();
        const price = $('#price').val().trim();
        const stock = $('#stock').val().trim();
        const category = $('#category').val();
        const newCategory = $('#new_category_input').is(':visible') ? $('#new_category_input').val().trim() : '';
        const productImage = $('#product_image').val().trim();

        console.log("Name:", name);
        console.log("Description:", description);
        console.log("Price:", price);
        console.log("Stock:", stock);
        console.log("Category:", category);
        console.log("New Category:", newCategory);
        console.log("Product Image:", productImage);

        if (name === '' || description === '' || price === '' || stock === '' || productImage === '') {
            errorMessage = "Please fill in all required fields.";
            isValid = false;
        } 
        else if (category === 'new' && newCategory === '') {
            errorMessage = "Please enter a new category name.";
            isValid = false;
        } 
        else if (category === '' || (category !== 'new' && $('#category option:selected').val() === '')) {
            errorMessage = "Please select a valid category.";
            isValid = false;
        }

        if (!isValid) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: errorMessage
            });
        } else {
            console.log("Form ready to submit!");
            $('#addProductForm').submit();
        }
    });
});
// edit
$(document).ready(function () {
            $('.edit-btn').on('click', function () {
                const id = $(this).data('id');
                const name = $(this).data('name');
                const description = $(this).data('description');
                const price = $(this).data('price');
                const stock = $(this).data('stock');
                const category = $(this).data('category');
                const image = $(this).data('image');

                $('#edit_product_id').val(id);
                $('#edit_name').val(name);
                $('#edit_description').val(description);
                $('#edit_price').val(price);
                $('#edit_stock').val(stock);
                $('#edit_category').val(category);  // This will update the category select input

                // Check if the category is 'new' and display the new category input
                if (category === 'new') {
                    $('#edit_new_category').show();
                    $('#edit_new_category_input').val(''); // Clear the input for new category
                } else {
                    $('#edit_new_category').hide();
                }

                $('#editProductModal').modal('show');
            });

            $('#edit_category').on('change', function () {
                if ($(this).val() === 'new') {
                    $('#edit_new_category').show();
                } else {
                    $('#edit_new_category').hide();
                    $('#edit_new_category_input').val('');  // Reset new category if hidden
                }
            });
        });
 
    </script>

</body>

</html>
