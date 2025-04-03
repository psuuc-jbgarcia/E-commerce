<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit();
}

require '../connection.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$category_query = "SELECT DISTINCT category FROM products";
$category_result = $conn->query($category_query);

$sql = "SELECT * FROM products WHERE 1";
$params = [];
$types = "";

if (!empty($category_filter)) {
    $sql .= " AND category = ?";
    $params[] = $category_filter;
    $types .= "s";
}

if (!empty($search_query)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $search_query . "%";
    $types .= "s";
}

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
            background-color: #F5EEF8;
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
            background-color: #7D3C98;
            border: none;
            padding: 10px 16px;
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #5B2C6F;
            transition: 0.3s;
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border-radius: 12px;
            overflow: hidden;
            background-color: #F5EEF8;
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
    </style>
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container content">
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
    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
    <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
    <input type="hidden" name="price" value="<?php echo $row['price']; ?>">
    <input type="hidden" name="image_name" value="<?php echo $row['image_name']; ?>">

    <div class="mb-3">
        <label for="quantity" class="form-label">Quantity</label>
        <input type="number" name="quantity" class="form-control" id="quantity" value="1" min="1" required>
    </div>

    <div class="d-grid gap-2 mt-3">
        <button type="submit" class="btn w-100" 
                style="background-color: #7D3C98; color: #FFFFFF; border: 2px solid #7D3C98; border-radius: 8px; padding: 10px 0; transition: background-color 0.3s ease-in-out;"
                onmouseover="this.style.backgroundColor='#5B2C6F';" 
                onmouseout="this.style.backgroundColor='#7D3C98';">
            <i class="fas fa-cart-plus me-1"></i> Add to Cart
        </button>
        <button type="button" class="btn w-100"
                style="background-color: #FFD700; color: #333333; border: 2px solid #FFD700; border-radius: 8px; padding: 10px 0; transition: background-color 0.3s ease-in-out;"
                onmouseover="this.style.backgroundColor='#F4D03F'; this.style.color='#000000';" 
                onmouseout="this.style.backgroundColor='#FFD700'; this.style.color='#333333';"
                onclick="openCheckoutModal('<?php echo $row['id']; ?>', '<?php echo $row['name']; ?>', '<?php echo $row['price']; ?>', 1, <?php echo $row['stock']; ?>)">
            <i class="fas fa-bolt me-1"></i> Buy Now
        </button>
    </div>
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

<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #F4D03F; color: #333333;">
                <h5 class="modal-title" id="checkoutModalLabel">Checkout Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <h5 class="text-center mb-4">Thank You for Shopping!</h5>

                <h6 class="fw-bold">Your Selected Item</h6>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span id="selected-product-name"></span>
                        <span>₱<span id="selected-product-price"></span></span>
                    </li>
                </ul>

                <div class="mb-3">
                    <h6 class="fw-bold">Shipping Address</h6>
                    <input type="text" class="form-control" id="shipping-address" name="address" value="<?php echo htmlspecialchars($_SESSION['address'] ?? ''); ?>" required>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Payment Method</h6>
                    <select class="form-select" name="payment_method" id="payment-method" required>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                    </select>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Shipping Fee</h6>
                    <p class="text-muted mb-0">A standard shipping fee of <strong>₱50.00</strong> applies.</p>
                    <p>Shipping Fee Total: ₱<span id="shipping-fee">50.00</span></p>
                </div>

                <div id="pin-input-section" class="mb-3">
                    <h6 class="fw-bold">Enter Secure CheckOut PIN</h6>
                    <input type="password" id="user-pin" class="form-control" placeholder="Enter your PIN" required maxlength="4">
                    <div id="pin-error-message" class="text-danger mt-2" style="display:none;">Incorrect PIN. Please try again.</div>
                </div>

                <div class="d-flex justify-content-between border-top pt-2">
                    <h5 class="fw-bold">Grand Total</h5>
                    <h5 class="fw-bold">₱<span id="total-price">0.00</span></h5>
                </div>
            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                
                <button type="button" class="btn btn-success" id="checkout-btn" style="display:none;" onclick="confirmOrder()">Confirm Order</button>

                <form action="buy_now.php" method="POST" id="checkoutForm" style="display:none;">
                    <input type="hidden" name="product_id" id="hidden-product-id">
                    <input type="hidden" name="product_name" id="hidden-product-name">
                    <input type="hidden" name="price" id="hidden-product-price">
                    <input type="hidden" name="total_price" id="hidden-total-price">
                    <input type="hidden" name="shipping_address" id="hidden-shipping-address">
                    <input type="text" name="payment_method" id="hidden-payment-method">
                    <button type="submit" class="btn btn-success placeorder">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <script>
 function applyFilters() {
    const searchQuery = document.getElementById('searchInput').value;
    const selectedCategory = document.getElementById('categoryFilter').value;
    const url = 'dashboard.php?search=' + encodeURIComponent(searchQuery) + '&category=' + encodeURIComponent(selectedCategory);
    
    
    console.log('Redirecting to:', url);

    window.location.href = url;
}
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('checkoutModal');
    const userPin = "<?php echo $_SESSION['pin']; ?>";
    const pinInput = document.getElementById('user-pin');
    const pinErrorMessage = document.getElementById('pin-error-message');
    const checkoutBtn = document.getElementById('checkout-btn');
    const checkoutForm = document.getElementById('checkoutForm');
    const pinInputSection = document.getElementById('pin-input-section');

    pinInput.addEventListener('input', function () {
        const enteredPin = pinInput.value;

       
        if (enteredPin === userPin) {
            checkoutBtn.style.display = 'block';
            pinInputSection.style.display = 'none';
            pinErrorMessage.style.display = 'none';
        } else {
            pinErrorMessage.style.display = 'block';
            checkoutBtn.style.display = 'none'; 
        }
    });

   
    checkoutBtn.addEventListener('click', function () {
        checkoutForm.style.display = 'block';
        checkoutBtn.style.display = 'none'; 

        checkoutForm.submit(); 
    });
});

        document.getElementById('payment-method').addEventListener('change', function () {
    const paymentMethod = this.value;
    console.log('Selected payment method:', paymentMethod);

    document.getElementById('hidden-payment-method').value = paymentMethod;
});



function openCheckoutModal(productId, productName, productPrice, quantity, stock) {
    document.getElementById('selected-product-name').innerText = productName;
    document.getElementById('selected-product-price').innerText = productPrice;
    document.getElementById('shipping-fee').innerText = '50.00'; 
    document.getElementById('total-price').innerText = (parseFloat(productPrice) + 50).toFixed(2);

    document.getElementById('hidden-product-id').value = productId;
    document.getElementById('hidden-product-name').value = productName;
    document.getElementById('hidden-product-price').value = productPrice;
    document.getElementById('hidden-total-price').value = (parseFloat(productPrice) + 50).toFixed(2);

    const paymentMethod = document.getElementById('payment-method').value; 
    document.getElementById('hidden-payment-method').value = paymentMethod; 

    var checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'));
    checkoutModal.show();
}


document.getElementById('checkoutForm').addEventListener('submit', function(event) {
    event.preventDefault(); 
    console.log('Form is being submitted...'); 

    this.submit(); 
});


    </script>

</body>
</html>
