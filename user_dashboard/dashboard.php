<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require '../connection.php';

// Get selected category and search term
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch unique categories for filter
$category_query = "SELECT DISTINCT category FROM products";
$category_result = $conn->query($category_query);

// Prepare query for search and category filter
$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types = "";

// Apply category filter
if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

// Apply search query
if (!empty($search_query)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $search_query . "%";
    $types .= "s";
}

// Prepare and execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Browse Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/global.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            box-sizing: border-box;
        }

      
     

        .content {
            flex: 1;
            margin-top: 90px;
            padding: 20px;
        }

        .filter-section {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .filter-section select,
        .filter-section input {
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .btn-primary {
            background-color: #4A90E2;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #357ABD;
            transition: 0.3s;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 12px;
            overflow: hidden;
            background-color: #fff;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .product-img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .footer {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
            margin-top: auto;
        }
    </style>
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container content">
        <!-- Search and Filter Section -->
        <div class="filter-section">
            <div class="row g-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Search for products..." value="<?php echo htmlspecialchars($search_query); ?>">
                </div>
                <div class="col-md-4">
                    <select id="categoryFilter" class="form-select">
                        <option value="">All Categories</option>
                        <?php
                        if ($category_result->num_rows > 0) {
                            while ($cat_row = $category_result->fetch_assoc()) {
                                $selected = ($cat_row['category'] == $category_filter) ? 'selected' : '';
                                echo "<option value='" . $cat_row['category'] . "' $selected>" . $cat_row['category'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100" onclick="applyFilters()"><i class="fas fa-search me-1"></i> Search</button>
                </div>
            </div>
        </div>

        <!-- Products Display -->
        <div class="row">
        <?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm">
                <img src="../uploads/<?php echo $row['image_name']; ?>" class="card-img-top product-img" alt="<?php echo $row['name']; ?>">
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?php echo $row['name']; ?></h5>
                    <p class="card-text text-muted"><?php echo $row['description']; ?></p>
                    <h6 class="text-success fw-bold mb-1">₱<?php echo number_format($row['price'], 2); ?></h6>
                    <h6 class="text-info mb-3">
    <?php 
        $stock = $row['stock'];

        if ($stock == 0) {
            echo '<span class="text-danger">Out of Stock</span>';
        } elseif ($stock <= 5) {
            echo '<span class="text-warning">Low Stock: ' . $stock . ' available</span>';
        } else {
            echo 'Stock: ' . $stock . ' available';
        }
    ?>
</h6>

                    <?php if ($row['stock'] > 0) { ?>
                        <form action="add_to_cart.php" method="POST">
                            <!-- Hidden inputs to pass the product details to the add_to_cart.php script -->
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                            <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
                            <input type="hidden" name="image_name" value="<?php echo $row['image_name']; ?>">

                            <!-- Quantity input field -->
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" min="1" max="<?php echo $row['stock']; ?>" value="1" required>
                                <span class="input-group-text">x</span>
                                <span class="input-group-text">₱<?php echo number_format($row['price'], 2); ?></span>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-cart-plus me-1"></i> Add to Cart
                            </button>
                        </form>
                    <?php } else { ?>
                        <button class="btn btn-secondary w-100" disabled><i class="fas fa-ban me-1"></i> Out of Stock</button>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo "<h5 class='text-center text-danger'>No products found!</h5>";
}
?>


        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

    <script>
        // Apply search and filter dynamically
        function applyFilters() {
            const searchQuery = document.getElementById('searchInput').value;
            const selectedCategory = document.getElementById('categoryFilter').value;
            const url = 'dashboard.php?search=' + encodeURIComponent(searchQuery) + '&category=' + encodeURIComponent(selectedCategory);
            window.location.href = url;
        }

        // Auto-filter on category change
        document.getElementById('categoryFilter').addEventListener('change', applyFilters);
    </script>
</body>

</html>
