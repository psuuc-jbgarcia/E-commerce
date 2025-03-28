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

$cart_sql = "SELECT * FROM cart WHERE user_id = ?";
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
<style>.quantity-display {
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
        <div class="text-end mb-3">
            <button type="button" class="btn btn-primary m-3" id="checkAllBtn" <?php echo empty($cart_items) ? 'style="display:none"' : ''; ?>>Check All</button>
        </div>

        <?php if (empty($cart_items)) { ?>
            <div class="alert alert-warning" role="alert">
                You have not added any items to the cart.
            </div>
        <?php } else { ?>
            <form id="checkout-form" action="checkout.php" method="POST">
                <div class="row">
                <?php foreach ($cart_items as $item) { ?>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body d-flex flex-column">
                <h5 class="card-title"><?php echo htmlspecialchars($item['product_name']); ?></h5>
                <img src="../uploads/<?php echo $item['image_name']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item['image_name']); ?>" style="max-height: 200px; object-fit: cover;">
                <p class="card-text">
                    <strong>Quantity:</strong> <?php echo $item['quantity']; ?><br>
                    <strong>Price:</strong> ₱<?php echo number_format($item['price'], 2); ?><br>
                    <strong>Total:</strong> ₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?>
                </p>

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" name="selected_items[]" value="<?php echo $item['id']; ?>" id="item-<?php echo $item['id']; ?>">
                        <label for="item-<?php echo $item['id']; ?>">Select</label>
                    </div>
                    <div class="d-flex align-items-center border rounded p-1 bg-light">
    <button type="button" class="btn btn-sm btn-outline-secondary quantity-decrease" data-item-id="<?php echo $item['id']; ?>">
        <i class="fa fa-minus"></i>
    </button>
    <span class="mx-3 fw-bold quantity-display"><?php echo $item['quantity']; ?></span>
    <button type="button" class="btn btn-sm btn-outline-secondary quantity-increase" data-item-id="<?php echo $item['id']; ?>">
        <i class="fa fa-plus"></i>
    </button>
</div>

                </div>
            </div>
        </div>
    </div>

  
<?php } ?>

                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-success mt-3" id="checkoutBtn">
                        Proceed to Checkout
                    </button>
                </div>
            </form>
        <?php } ?>
    </div>
          <!-- Modal for Deletion Confirmation with Unique ID -->
          <div class="modal fade" id="deleteModal-<?php echo $item['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel-<?php echo $item['id']; ?>" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteModalLabel-<?php echo $item['id']; ?>">Confirm Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to delete the item "<?php echo htmlspecialchars($item['product_name']); ?>" from your cart?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <form action="remove_item.php" method="POST" class="d-inline">
                                            <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                            <button type="submit" class="btn btn-danger">Confirm Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
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
                    <ul id="selected-items-list" class="list-group mb-3">
                    </ul>
                    <hr>

                    <div class="mb-3">
                        <h5>Shipping Address</h5>
                        <input type="text" class="form-control" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($user_address); ?>" required>
                    </div>

                    <div class="mb-3">
                        <h5>Payment Method</h5>
                        <select class="form-select" name="payment_method" required>
                            <option value="credit_card">Credit Card</option>
                            <option value="paypal">PayPal</option>
                            <option value="cash_on_delivery">Cash on Delivery</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <h5>Shipping Fee</h5>
                        <p>₱<span id="shipping-fee">50.00</span></p>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <h5>Total Price</h5>
                        <h5>₱<span id="total-price">0.00</span></h5>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            const selectedItemsList = document.getElementById('selected-items-list');
            const totalPriceElement = document.getElementById('total-price');
            const shippingFeeElement = document.getElementById('shipping-fee');
            const checkoutBtn = document.getElementById('checkoutBtn');
            let totalPrice = 0;

            // Hide checkout button if cart is empty
            if (checkboxes.length === 0) {
                checkoutBtn.style.display = "none";
            }

            // Update the total price when checkboxes are checked/unchecked
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

                // Check if any checkbox is selected
                checkboxes.forEach((checkbox) => {
                    if (checkbox.checked) {
                        anySelected = true;
                    }
                });

                // If no items are selected, show the SweetAlert and prevent modal from opening
                if (!anySelected) {
                    event.preventDefault(); // Prevent modal from opening
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one item to proceed to checkout.',
                    });
                } else {
                    // Proceed to open the modal only if at least one item is selected
                    const checkoutModal = new bootstrap.Modal(document.getElementById('checkoutModal'), {
    backdrop: 'static', // Prevents modal from closing when clicking outside it
    keyboard: false     // Disables closing with the keyboard (e.g., ESC key)
});
checkoutModal.show();

                }
            });
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
                } else if (this.classList.contains("quantity-decrease") && currentQuantity > 1) {
                    currentQuantity--;
                }

                // Send AJAX request to update quantity in database
                fetch("update_cart_quantity.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: `cart_id=${itemId}&quantity=${currentQuantity}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        quantityDisplay.textContent = currentQuantity;
                        location.reload(); // Refresh page to update total price
                    } else {
                        alert("Failed to update quantity.");
                    }
                })
                .catch(error => console.error("Error:", error));
            });
        });
    });
</script>
</body>

</html>
