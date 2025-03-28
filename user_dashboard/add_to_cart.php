<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require '../connection.php';

// Ensure that product_id and other required POST variables are set and are valid
if (isset($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['quantity'], $_POST['image_name'])) {
    // Get product details from the form submission
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $image_name = isset($_POST['image_name']) ? $_POST['image_name'] : '';

    $user_id = $_SESSION['user_id'];  // Get the logged-in user ID

    // Ensure valid data is provided
    if ($product_id > 0 && $product_name && $price >= 0 && $quantity > 0 && $image_name) {
        // Calculate total price based on quantity
        $total_price = $price * $quantity;

        // Insert into the cart table
        $cart_sql = "INSERT INTO cart (user_id, product_id, product_name, quantity, price, total_price, image_name) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE quantity = quantity + ?, total_price = total_price + ?";
        $cart_stmt = $conn->prepare($cart_sql);
        
        // Debug: Ensure that the right variables are being passed
        // echo '<pre>';
        // print_r([$user_id, $product_id, $product_name, $quantity, $price, $total_price, $image_name, $quantity, $total_price]);
        // echo '</pre>';
        
        // Correct bind_param with matching placeholders (9 parameters in total)
        $cart_stmt->bind_param("iissddiis", $user_id, $product_id, $product_name, $quantity, $price, $total_price, $quantity, $total_price, $image_name,);
        
        $cart_stmt->execute();

        // Display SweetAlert after adding to cart
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Added to Cart!',
                        text: 'Product has been added to your cart.',
                        timer: 1000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'dashboard.php';
                    });
                });
              </script>";
        exit();
    } else {
        // Handle invalid data
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Product details are missing or invalid.',
                        timer: 1000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'dashboard.php';
                    });
                });
              </script>";
        exit();
    }
} else {
    // Handle case where the required POST data is not provided
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Data',
                    text: 'Product ID or other details are missing.',
                    timer: 100,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'dashboard.php';
                });
            });
          </script>";
    exit();
}
?>
