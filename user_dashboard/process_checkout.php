<?php
session_start();
require '../connection.php';  

function showAlert($title, $message, $icon)
{
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '$title',
                text: '$message',
                icon: '$icon',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = 'cart.php';
            });
        });
    </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($_POST['product_ids']) &&
        isset($_POST['product_names']) &&
        isset($_POST['quantities']) &&
        isset($_POST['payment_method']) &&
        isset($_POST['shipping_fee_total']) &&
        isset($_POST['grand_total']) &&
        isset($_POST['tracking_code'])
    ) {
        if (!isset($_SESSION['email']) || !isset($_SESSION['number']) || !isset($_SESSION['address'])) {
            showAlert('Error', 'User session data is missing.', 'error');
        }

        $username = $_SESSION['email'];
        $contact_number = $_SESSION['number'];
        $shipping_address = $_SESSION['address'];

        $tracking_code = $_POST['tracking_code'];
        $product_ids = $_POST['product_ids'];
        $product_names = $_POST['product_names'];
        $quantities = $_POST['quantities'];
        $payment_method = $_POST['payment_method'];
        $shipping_fee_total = floatval($_POST['shipping_fee_total']);
        $grand_total = floatval($_POST['grand_total']);
        $order_status = 'Pending';
        $order_date = date('Y-m-d');

        $product_ids_str = implode(", ", $product_ids); // Comma separated product IDs
        $product_names_str = implode(", ", $product_names);
        $quantities_str = implode(", ", $quantities);

       


$conn->begin_transaction();

try {
    $stmt = $conn->prepare("
        INSERT INTO orders (
            tracking_code, username, shipping_address, contact_number, product_ids,
            product_names, quantities, payment_method, shipping_fee_total, grand_total,
            order_status, order_date
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssssssddss",
        $tracking_code, $username, $shipping_address, $contact_number,
        $product_ids_str, $product_names_str, $quantities_str, $payment_method,
        $shipping_fee_total, $grand_total, $order_status, $order_date
    );

    if (!$stmt->execute()) {
        throw new Exception("Error inserting order: " . $stmt->error);
    }

    $order_id = $stmt->insert_id;

  

    for ($i = 0; $i < count($product_ids); $i++) {
        $product_id = intval($product_ids[$i]);
        $quantity = intval($quantities[$i]);

        $check_stock_stmt = $conn->prepare("SELECT stock, name FROM products WHERE id = ?");
        $check_stock_stmt->bind_param("i", $product_id);
        $check_stock_stmt->execute();
        $result = $check_stock_stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_name = $row['name'];  

            if ($row['stock'] < $quantity) {
                throw new Exception("Insufficient stock for product: $product_name");
            }

            $update_stock_stmt = $conn->prepare("
                UPDATE products 
                SET stock = stock - ? 
                WHERE id = ?
            ");
            $update_stock_stmt->bind_param("ii", $quantity, $product_id);

            if (!$update_stock_stmt->execute()) {
                throw new Exception("Error updating stock for product: " . $product_name);
            }

            $delete_cart_stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
            $delete_cart_stmt->bind_param("i", $product_id);
            if (!$delete_cart_stmt->execute()) {
                throw new Exception("Error removing item from cart for product: " . $product_name);
            }
        } else {
            throw new Exception("Product '$product_id' not found.");
        }
    }

    $conn->commit();

    showAlert('Success', 'Your order has been placed successfully!', 'success');

} catch (Exception $e) {
    $conn->rollback();

    showAlert('Error', $e->getMessage(), 'error');
}

        }
  
} else {
    showAlert('Error', 'Invalid request method.', 'error');
}

?>
