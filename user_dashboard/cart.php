<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
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

$cart_sql = "SELECT * FROM cart WHERE user_id = ? AND CAST(quantity AS UNSIGNED) > 0";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../static/css/global.css">
</head>
<style>
.quantity-display {
    min-width: 30px;
    text-align: center;
}
.btn-outline-secondary {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

<body>
    <?php include 'navigation.php'; ?>

    <div class="container mt-5">
    <div class="text-end mb-3 d-flex justify-content-between align-items-center">
    <div class="text-muted" style="font-size: 1rem !important; color:wheat !important;">
    Select items to proceed with multiple order actions.    </div>
    
    <button type="button" class="btn m-3" id="checkAllBtn" 
        style="background-color: #7D3C98; color: #FFFFFF;" 
        onmouseover="this.style.backgroundColor='#F4D03F'; this.style.color='#333333';" 
        onmouseout="this.style.backgroundColor='#7D3C98'; this.style.color='#FFFFFF';">
        Check All
    </button>
</div>


        <?php if (empty($cart_items)) { ?>
            <div class="alert alert-warning" role="alert">
                You have not added any items to the cart.
            </div>
        <?php } else { ?>
            <form id="checkout-form" action="process_checkout.php" method="POST">
    <div class="row g-4">
        <?php foreach ($cart_items as $item) { ?>
            <div class="col-md-4 mb-4">
    <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important; border: 2px solid #FFD700 !important; border-radius: 15px !important; background-color: #7D3C98 !important;">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-center" style="font-size: 1.25rem !important; font-weight: bold !important; color: #FFD700 !important;"><?php echo htmlspecialchars($item['product_name']); ?></h5>
            <input type="hidden" class="product-id" value="<?php echo htmlspecialchars($item['product_id']); ?>">

            <img src="https://www.collinsdictionary.com/images/full/apple_158989157.jpg" class="card-img-top" alt="<?php echo htmlspecialchars($item['image_name']); ?>" style="max-height: 200px !important; object-fit: cover !important; border-radius: 10px !important; border: 2px solid #FFD700 !important;">
            
            <p class="card-text mt-3" style="font-size: 1rem !important; color: #F7DC6F !important;">
                <strong style="color: #FFD700;">Quantity:</strong> <?php echo $item['quantity']; ?><br>
                <strong style="color: #FFD700;">Price:</strong> ₱<?php echo number_format($item['price'], 2); ?><br>
                <strong style="color: #FFD700;">Total:</strong> ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
            </p>

            <div class="d-flex justify-content-between align-items-center mt-3">
                <!-- Custom Checkbox -->
                <div class="form-check" style="position: relative;">
                    <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>" class="form-check-input" style="position: absolute; opacity: 0 !important; z-index: 1 !important;">
                    <label for="item-<?php echo $item['id']; ?>" class="custom-checkbox-label" style="display: inline-block; width: 22px; height: 22px; border-radius: 5px; border: 2px solid #FFD700; background-color: #7D3C98; position: relative; cursor: pointer; transition: background-color 0.3s ease-in-out !important; z-index: 0 !important;">
                        <i class="fa fa-check" style="position: absolute; top: 3px; left: 3px; font-size: 16px; color: #FFD700; opacity: 0; transition: opacity 0.3s ease-in-out !important;"></i>
                    </label>
                </div>

                <!-- Quantity Controls -->
                <div class="d-flex align-items-center border rounded p-2" style="border: 1px solid #FFD700 !important; background-color: #5B2C6F !important;">
                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-decrease" data-item-id="<?php echo $item['id']; ?>" style="border-radius: 50% !important; padding: 5px 10px !important; border-color: #FFD700 !important; background-color: #5B2C6F !important;">
                        <i class="fa fa-minus" style="font-size: 1rem !important; color: #FFD700;"></i>
                    </button>
                    <span class="mx-3 fw-bold quantity-display" style="font-size: 1.1rem !important; color: #FFD700;"><?php echo $item['quantity']; ?></span>
                    <button type="button" class="btn btn-sm btn-outline-secondary quantity-increase" data-item-id="<?php echo $item['id']; ?>" style="border-radius: 50% !important; padding: 5px 10px !important; border-color: #FFD700 !important; background-color: #5B2C6F !important;">
                        <i class="fa fa-plus" style="font-size: 1rem !important; color: #FFD700;"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

        <?php } ?>
    </div>

    <div class="text-center">
    <button type="button" class="btn btn-sm px-5 py-3" id="checkoutBtn" 
    style="background-color: #7D3C98 !important; color: #FFFFFF !important; border-radius: 50px !important;
    position: fixed; bottom: 80px; left: 50%; transform: translateX(-50%); z-index: 1000;" 
    onmouseover="this.style.backgroundColor='#F4D03F'; this.style.color='#333333';" 
    onmouseout="this.style.backgroundColor='#7D3C98'; this.style.color='#FFFFFF';">
        <i class="fa fa-shopping-cart"></i> Proceed to Checkout
    </button>
</div>

</form>



        <?php } ?>
    </div>

    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #F4D03F; color: #333333;">
                    <h5 class="modal-title" id="checkoutModalLabel">🧾 Checkout Receipt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <h5 class="text-center mb-4">Thank You for Shopping!</h5>

                    <!-- Selected Items List -->
                    <h6 class="fw-bold">📦 Your Selected Items</h6>
                    <ul id="selected-items-list" class="list-group mb-3"></ul>

                    <!-- Shipping Address -->
                    <div class="mb-3">
                        <h6 class="fw-bold">📍 Shipping Address</h6>
                        <input type="text" class="form-control" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($user_address); ?>" required>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-3">
                        <h6 class="fw-bold">💳 Payment Method</h6>
                        <select class="form-select" name="payment_method" required>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>

                    <!-- Shipping Fee -->
                    <div class="mb-3">
                        <h6 class="fw-bold">🚚 Shipping Fee</h6>
                        <p class="text-muted mb-0">A standard shipping fee of <strong>₱50.00 per item</strong> will be applied.</p>
                        <p>📢 <span class="text-info">Shipping Fee Total:</span> ₱<span id="shipping-fee">0.00</span></p>
                    </div>

                    <!-- Total Price -->
                    <div class="d-flex justify-content-between border-top pt-2">
                        <h5 class="fw-bold">💰 Grand Total</h5>
                        <h5 class="fw-bold">₱<span id="total-price">0.00</span></h5>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form action="process_checkout.php" method="POST" id="checkoutForm">
                        <div id="hidden-inputs-container"></div>
                        <button type="submit" class="btn btn-success placeorder">Confirm Order</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
        const selectedItemsList = document.getElementById('selected-items-list');
        const totalPriceElement = document.getElementById('total-price');
        const shippingFeeElement = document.getElementById('shipping-fee');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const hiddenInputsContainer = document.getElementById('hidden-inputs-container');
        const paymentMethodSelect = document.querySelector('select[name="payment_method"]');
        let totalPrice = 0;
        let shippingFee = 0;

        // Array to store selected product data
        let selectedProducts = [];

        // Hide checkout button if cart is empty
        if (checkboxes.length === 0) {
            checkoutBtn.style.display = "none";
        }

        // Update total price and selected product data when checkboxes are checked/unchecked
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                updateSelectedProducts();
                updateHiddenInputs();
            });
        });

        // "Check All" button functionality
        const checkAllBtn = document.getElementById('checkAllBtn');
        checkAllBtn.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
                checkbox.dispatchEvent(new Event('change'));
            });
        });

        // Add event listener to the "Proceed to Checkout" button
        checkoutBtn.addEventListener('click', (event) => {
            let anySelected = false;
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    anySelected = true;
                }
            });

            if (!anySelected) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No Items Selected',
                    text: 'Please select at least one item to proceed to checkout.',
                });
            } else {
                const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'), {
                    backdrop: 'static',
                    keyboard: false
                });
                checkoutModal.show();
            }
        });

        // Function to update selected products
        function updateSelectedProducts() {
            selectedItemsList.innerHTML = '';
            hiddenInputsContainer.innerHTML = '';
            totalPrice = 0;
            shippingFee = 0;
            selectedProducts = [];

            checkboxes.forEach((checkbox) => {
    if (checkbox.checked) {
        const itemCard = checkbox.closest('.card-body');
        const itemName = itemCard.querySelector('.card-title').textContent.trim();
        const itemPrice = parseFloat(itemCard.querySelector('.card-text').querySelector('strong:nth-child(3)').nextSibling.textContent.replace('₱', '').trim());
        const itemQuantity = parseInt(itemCard.querySelector('.quantity-display').textContent.trim());
        const productId = itemCard.querySelector('.product-id').value;

        const itemTotal = itemPrice * itemQuantity;

        // Apply shipping fee as ₱50 per product, not per quantity
        const itemShippingFee = 50; // Fixed shipping fee per product

        totalPrice += itemTotal + itemShippingFee;
        shippingFee += itemShippingFee;

        selectedProducts.push({
            product_id: productId,
            item_name: itemName,
            quantity: itemQuantity,
            shipping_fee: itemShippingFee,
            item_total: itemTotal + itemShippingFee
        });

        const listItem = document.createElement('li');
        // (ID: ${productId})
        listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        listItem.innerHTML = `${itemName}  - ₱${itemPrice} x ${itemQuantity} <span>+ Shipping: ₱${itemShippingFee.toFixed(2)}</span>`;
        selectedItemsList.appendChild(listItem);
    }
});


            shippingFeeElement.textContent = shippingFee.toFixed(2);
            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        // Function to generate a random tracking code
        function generateTrackingCode() {
            const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let trackingCode = 'TRK';
            for (let i = 0; i < 8; i++) {
                trackingCode += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            return trackingCode;
        }

        // Function to update hidden inputs
        function updateHiddenInputs() {
            let productIds = [], itemNames = [], quantities = [];
            const trackingCode = generateTrackingCode();

            selectedProducts.forEach((product) => {
                productIds.push(product.product_id);
                itemNames.push(product.item_name);
                quantities.push(product.quantity);
            });

            hiddenInputsContainer.innerHTML = `
    ${productIds.map((id, index) => `
        <input type="hidden" name="product_ids[]" value="${id}">
        <input type="hidden" name="product_names[]" value="${itemNames[index]}">
        <input type="hidden" name="quantities[]" value="${quantities[index]}">
    `).join('')}
    
    <input type="hidden" name="payment_method" value="${paymentMethodSelect.value}">
    <input type="hidden" name="shipping_fee_total" value="${shippingFee.toFixed(2)}">
    <input type="hidden" name="grand_total" value="${totalPrice.toFixed(2)}">
    <input type="hidden" name="tracking_code" value="${trackingCode}">
`;

        }
    });
    </script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".quantity-increase, .quantity-decrease").forEach(button => {
            button.addEventListener("click", function () {
                const itemId = this.getAttribute("data-item-id");
                const quantityDisplay = this.closest(".d-flex").querySelector(".quantity-display");
                let currentQuantity = parseInt(quantityDisplay.textContent);

                // Determine if increasing or decreasing
                if (this.classList.contains("quantity-increase")) {
                    currentQuantity++;
                } else if (this.classList.contains("quantity-decrease") && currentQuantity > 0) {
                    currentQuantity--;
                }

                fetch("update_cart_quantity.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        item_id: itemId,
                        action: this.classList.contains("quantity-increase") ? "increase" : "decrease",
                        quantity: currentQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (currentQuantity === 0) {
                            // Remove the item from the DOM when quantity reaches zero
                            this.closest('.col-md-4').remove();
                        } else {
                            // Update the displayed quantity
                            quantityDisplay.textContent = currentQuantity;
                        }
                    } else {
                        alert("Failed to update quantity.");
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
</script>
<script>
    document.querySelectorAll('.form-check-input').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const label = this.nextElementSibling;
            const checkmark = label.querySelector('i');

            if (this.checked) {
                checkmark.style.opacity = 1;
            } else {
                checkmark.style.opacity = 0;
            }
        });
    });
</script>
</body>

</html>
