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
    <div class="card" style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important; border: 2px solidrgb(255, 255, 255) !important; border-radius: 15px !important; background-color:white #7D3C98 !important;">
        <div class="card-body d-flex flex-column">
            <h5 class="card-title text-center" style="font-size: 1.25rem !important; font-weight: bold !important; color: #333333 !important;"><?php echo htmlspecialchars($item['product_name']); ?></h5>
            <input type="hidden" class="product-id" value="<?php echo htmlspecialchars($item['product_id']); ?>">

            <img src="../uploads/<?= $item['image_name'] ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['image_name']); ?>" style="max-height: 200px !important; object-fit: cover !important; border-radius: 10px !important; border: 2px solidrgb(26, 24, 15) !important;">
            
            <p class="card-text mt-3" style="font-size: 1rem !important; color: #7D3C98 !important;">
                <strong style="color:#333333;">Quantity:</strong> <?php echo $item['quantity']; ?><br>
                <strong style="color:#333333;">Price:</strong> ₱<?php echo number_format($item['price'], 2); ?><br>
                <strong style="color: #333333;">Total:</strong> ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
            </p>

            <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-check">
    <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>" class="form-check-input" style="border: 2px solid black;">
    <label for="item-<?php echo $item['id']; ?>" class="form-check-label">
        Select Item
    </label>
</div>


                <div class="d-flex align-items-center border rounded p-2" style="border: 1px solid black;">
    <button type="button" class="btn btn-sm quantity-decrease" data-item-id="<?php echo $item['id']; ?>">
        <i class="fa fa-minus" style="font-size: 1rem;"></i>
    </button>
    <span class="mx-3 fw-bold quantity-display" style="font-size: 1.1rem;"><?php echo $item['quantity']; ?></span>
    <button type="button" class="btn btn-sm quantity-increase" data-item-id="<?php echo $item['id']; ?>">
        <i class="fa fa-plus" style="font-size: 1rem;"></i>
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
                <h5 class="modal-title" id="checkoutModalLabel">Checkout Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <h5 class="text-center mb-4">Thank You for Shopping!</h5>

                <h6 class="fw-bold">Your Selected Items</h6>
                <ul id="selected-items-list" class="list-group mb-3"></ul>

                <div class="mb-3">
                    <h6 class="fw-bold">Shipping Address</h6>
                    <input type="text" class="form-control" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($user_address); ?>" required>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Payment Method</h6>
                    <select class="form-select" name="payment_method" required>
                        <option value="cash_on_delivery">Cash on Delivery</option>
                    </select>
                </div>

                <div class="mb-3">
                    <h6 class="fw-bold">Shipping Fee</h6>
                    <p class="text-muted mb-0">A standard shipping fee of <strong>₱50.00 per item</strong> will be applied.</p>
                    <p>Shipping Fee Total: ₱<span id="shipping-fee">0.00</span></p>
                </div>

                <div class="d-flex justify-content-between border-top pt-2">
                    <h5 class="fw-bold">Grand Total</h5>
                    <h5 class="fw-bold">₱<span id="total-price">0.00</span></h5>
                </div>

<div id="pin-input-section" class="mb-3">
    <h6 class="fw-bold">Enter Secure CheckOut PIN</h6>
    <input type="password" id="user-pin" class="form-control" placeholder="Enter your PIN" required maxlength="4">
    <div id="pin-error-message" class="text-danger mt-2" style="display:none;">Incorrect PIN. Please try again.</div>
</div>


            </div>

            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <form action="process_checkout.php" method="POST" id="checkoutForm">
                    <div id="hidden-inputs-container"></div>
                    <!-- Hidden checkout button initially -->
                    <button type="submit" class="btn btn-success placeorder" id="checkout-btn" style="display: none;">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>
</div>


    <div class="footer">
        &copy; <?php echo date('Y'); ?> Small Shop Inventory. All Rights Reserved.
    </div>

    <script>
    // Make sure this script is placed after the modal HTML code
    document.addEventListener('DOMContentLoaded', function() {
        const userPin = "<?php echo $_SESSION['pin']; ?>"; // Retrieve the PIN from the session
        const pinInput = document.getElementById('user-pin');
        const checkoutBtn = document.getElementById('checkout-btn');
        const pinInputSection = document.getElementById('pin-input-section');
        const pinErrorMessage = document.getElementById('pin-error-message');

        pinInput.addEventListener('input', function() {
            const enteredPin = pinInput.value;

            // Check if the entered PIN matches the user's PIN stored in the session
            if (enteredPin === userPin) {
                // Show the checkout button and hide the PIN input section
                checkoutBtn.style.display = 'block';
                pinInputSection.style.display = 'none';
                pinErrorMessage.style.display = 'none';
            } else {
                // Show error message if PIN is incorrect
                pinErrorMessage.style.display = 'block';
                checkoutBtn.style.display = 'none'; // Keep the checkout button hidden
            }
        });
    });
</script>

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

        let selectedProducts = [];

        if (checkboxes.length === 0) {
            checkoutBtn.style.display = "none";
        }

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', () => {
                updateSelectedProducts();
                updateHiddenInputs();
            });
        });

        const checkAllBtn = document.getElementById('checkAllBtn');
        checkAllBtn.addEventListener('click', () => {
            checkboxes.forEach((checkbox) => {
                checkbox.checked = true;
                checkbox.dispatchEvent(new Event('change'));
            });
        });

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

        const itemShippingFee = 50; 

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

        function generateTrackingCode() {
            const charset = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let trackingCode = 'TRK';
            for (let i = 0; i < 8; i++) {
                trackingCode += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            return trackingCode;
        }

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
                            this.closest('.col-md-4').remove();
                        } else {
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
