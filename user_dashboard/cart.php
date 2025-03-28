<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$address_sql = "SELECT address FROM users WHERE id = ?";
$address_stmt = $conn->prepare($address_sql);
$address_stmt->bind_param("i", $user_id);
$address_stmt->execute();
$address_result = $address_stmt->get_result();

$user_address = '';
if ($address_result->num_rows > 0) {
    $address_row = $address_result->fetch_assoc();
    $user_address = $address_row['address'];
}

$cart_sql = "SELECT id, user_id, product_id, product_name, quantity, price FROM cart WHERE user_id = ?";
$cart_stmt = $conn->prepare($cart_sql);
$cart_stmt->bind_param("i", $user_id);
$cart_stmt->execute();
$cart_result = $cart_stmt->get_result();

$cart_items = [];
$total_price = 0;

while ($row = $cart_result->fetch_assoc()) {
    $cart_items[] = $row;
    $total_price += $row['price'] * $row['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart - Small Shop Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../static/css/global.css">
</head>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary m-3" id="checkAllBtn">Check All</button>
        </div>

        <form id="checkout-form" action="checkout.php" method="POST">
            <div class="row">
                <?php foreach ($cart_items as $item) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($item['product_name']); ?></h5>
                                <p class="card-text">
                                    <strong>Quantity:</strong> <?php echo $item['quantity']; ?><br>
                                    <strong>Price:</strong> ₱<?php echo number_format($item['price'], 2); ?><br>
                                    <strong>Total:</strong> ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                                </p>
                                <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>">
                                <label for="item-<?php echo $item['id']; ?>">Select</label>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                    Proceed to Checkout
                </button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkoutModalLabel">Checkout Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5>Your Selected Items</h5>
                    <ul id="selected-items-list">
                    </ul>
                    <hr>
                    <h5>Total Price: ₱<span id="total-price">0.00</span></h5>
                    
                    <h5>Address</h5>
                    <input type="text" class="form-control mb-3" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($user_address); ?>" required>

                    <h5>Payment Method</h5>
                    <select class="form-select mb-3" name="payment_method" required>
                        <option value="credit_card">Credit Card</option>
                        <option value="paypal">PayPal</option>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                    </select>

                    <h5>Shipping Fee</h5>
                    <p>₱<span id="shipping-fee">50.00</span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success">Confirm Order</button>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
        const selectedItemsList = document.getElementById('selected-items-list');
        const totalPriceElement = document.getElementById('total-price');
        const shippingFeeElement = document.getElementById('shipping-fee');
        let totalPrice = 0;

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                selectedItemsList.innerHTML = '';
                totalPrice = 0;

                checkboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        const itemId = checkbox.value;
                        const itemName = checkbox.closest('.card-body').querySelector('.card-title').textContent;
                        const itemPrice = parseFloat(checkbox.closest('.card-body').querySelector('.card-text').querySelector('strong:nth-child(3)').nextSibling.textContent.replace('₱', '').trim());
                        const itemQuantity = parseInt(checkbox.closest('.card-body').querySelector('.card-text').querySelector('strong:nth-child(1)').nextSibling.textContent.trim());

                        totalPrice += itemPrice * itemQuantity;

                        const listItem = document.createElement('li');
                        listItem.textContent = `${itemName} - ₱${itemPrice} x ${itemQuantity}`;
                        selectedItemsList.appendChild(listItem);
                    }
                });

                totalPriceElement.textContent = totalPrice.toFixed(2);
            });
        });

        const checkAllBtn = document.getElementById('checkAllBtn');
        checkAllBtn.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
                checkbox.dispatchEvent(new Event('change'));
            });
        });
    </script>
</body>

</html>
