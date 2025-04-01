<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['email'])) {
    header("Location: ../authentication/login.php");
    exit();
}

require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
    $product_name = isset($_POST['product_name']) ? $_POST['product_name'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $shipping_address = isset($_POST['shipping_address']) ? $_POST['shipping_address'] : $_SESSION['address']; 
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash_on_delivery'; 

    $quantity = 1;

    if (!$product_id || !$product_name || !$price) {
        echo "<script>alert('Missing product details. Please try again.'); window.history.back();</script>";
        exit();
    }

    $username = $_SESSION['email'];

    $shipping_fee = 50.00;
    $grand_total = ($quantity * $price) + $shipping_fee;

    $tracking_code = "TRK" . strtoupper(substr(md5(time()), 0, 10));

    $stmt = $conn->prepare("INSERT INTO orders 
        (tracking_code, username, shipping_address, contact_number, product_ids, product_names, quantities, payment_method, shipping_fee_total, grand_total, order_status, order_date) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending', NOW())");

    $stmt->bind_param("ssssssssdd", 
        $tracking_code, 
        $username,  
        $shipping_address, 
        $_SESSION['number'], 
        $product_id, 
        $product_name, 
        $quantity, 
        $payment_method, 
        $shipping_fee, 
        $grand_total
    );

    if ($stmt->execute()) {
        $stmt = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();

        echo "<script>
                Swal.fire({
                    title: 'Order placed successfully!',
                    text: 'Your tracking code is $tracking_code.',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = 'dashboard.php';
                });
              </script>";
    } else {
        echo "<script>
                alert('Failed to place order. Please try again.');
                window.history.back();
              </script>";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<script>
    document.getElementById('payment-method').addEventListener('change', function() {
        const paymentMethod = this.value;
        document.getElementById('hidden-payment-method').value = paymentMethod;
    });
</script>

</body>
</html>
