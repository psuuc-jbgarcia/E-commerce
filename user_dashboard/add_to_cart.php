<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit();
}

require '../connection.php';
if (isset($_POST['product_id'], $_POST['product_name'], $_POST['price'], $_POST['quantity'], $_POST['image_name'])) {
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : '';
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $image_name = isset($_POST['image_name']) ? $_POST['image_name'] : '';
    $user_id = $_SESSION['user_id'];

    if ($product_id > 0 && $product_name && $price >= 0 && $quantity > 0 && $image_name) {
        $total_price = $price * $quantity;

        $cart_sql = "INSERT INTO cart (user_id, product_id, product_name, quantity, price, total_price, image_name) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)
                     ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity), 
                     total_price = total_price + VALUES(total_price)";
        $cart_stmt = $conn->prepare($cart_sql);

        $cart_stmt->bind_param("iissdds", $user_id, $product_id, $product_name, $quantity, $price, $total_price, $image_name);
        $cart_stmt->execute();

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
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Product details are missing or invalid.',
                        timer: 500,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = 'dashboard.php';
                    });
                });
              </script>";
        exit();
    }
} else {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
    echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Missing Data',
                    text: 'Product ID or other details are missing.',
                    timer: 1000,
                    showConfirmButton: false
                }).then(function() {
                    window.location.href = 'dashboard.php';
                });
            });
          </script>";
    exit();
}
?>
