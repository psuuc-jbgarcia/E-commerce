<?php
session_start();
require '../connection.php';  // Include DB connection

// SweetAlert function
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

// Check if request is POST
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
        // Get user details from session
        if (!isset($_SESSION['email']) || !isset($_SESSION['number']) || !isset($_SESSION['address'])) {
            showAlert('Error', 'User session data is missing.', 'error');
        }

        $username = $_SESSION['email'];
        $contact_number = $_SESSION['number'];
        $shipping_address = $_SESSION['address'];

        // Get order details from form
        $tracking_code = $_POST['tracking_code'];
        $product_ids = $_POST['product_ids'];
        $product_names = $_POST['product_names'];
        $quantities = $_POST['quantities'];
        $payment_method = $_POST['payment_method'];
        $shipping_fee_total = floatval($_POST['shipping_fee_total']);
        $grand_total = floatval($_POST['grand_total']);
        $order_status = 'Pending';
        $order_date = date('Y-m-d');

        // Combine arrays for insertion
        $product_ids_str = implode(", ", $product_ids);
        $product_names_str = implode(", ", $product_names);
        $quantities_str = implode(", ", $quantities);

        // Insert data into the orders table
        $stmt = $conn->prepare("
            INSERT INTO orders (
                tracking_code, username, shipping_address, contact_number, product_ids,
                product_names, quantities, payment_method, shipping_fee_total, grand_total,
                order_status, order_date
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt === false) {
            showAlert('Error', 'Error preparing SQL statement: ' . $conn->error, 'error');
        }

        $stmt->bind_param(
            "ssssssssddss",
            $tracking_code, $username, $shipping_address, $contact_number,
            $product_ids_str, $product_names_str, $quantities_str, $payment_method,
            $shipping_fee_total, $grand_total, $order_status, $order_date
        );

        if (!$stmt->execute()) {
            showAlert('Error', 'Error inserting order: ' . $stmt->error, 'error');
        }

        // Get the last inserted order ID
        $order_id = $stmt->insert_id;

        // Update stock for each product
        for ($i = 0; $i < count($product_ids); $i++) {
            $product_id = intval($product_ids[$i]);
            $quantity = intval($quantities[$i]);

            // Check stock availability before updating
            $check_stock_stmt = $conn->prepare("SELECT stock, name FROM products WHERE id = ?");
            $check_stock_stmt->bind_param("i", $product_id);
            $check_stock_stmt->execute();
            $result = $check_stock_stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $product_name = $row['name'];  // Get product name

                if ($row['stock'] < $quantity) {
                    showAlert('Error', "Insufficient stock for product: $product_name", 'error');
                }
            } else {
                showAlert('Error', "Product '$product_id' not found.", 'error');
            }

            // Update stock in the products table
            $update_stock_stmt = $conn->prepare("
                UPDATE products 
                SET stock = stock - ? 
                WHERE id = ?
            ");

            if ($update_stock_stmt === false) {
                showAlert('Error', 'Error preparing stock update: ' . $conn->error, 'error');
            }

            $update_stock_stmt->bind_param("ii", $quantity, $product_id);

            if (!$update_stock_stmt->execute()) {
                showAlert('Error', 'Error updating stock: ' . $update_stock_stmt->error, 'error');
            }

            // Get updated stock after order
            $new_stock_stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
            $new_stock_stmt->bind_param("i", $product_id);
            $new_stock_stmt->execute();
            $new_result = $new_stock_stmt->get_result();

            if ($new_result->num_rows > 0) {
                $new_row = $new_result->fetch_assoc();
                $new_stock = $new_row['stock'];

                // Remove item from cart if quantity is zero
                if ($new_stock <= 0) {
                    $delete_cart_stmt = $conn->prepare("DELETE FROM cart WHERE product_id = ?");
                    $delete_cart_stmt->bind_param("i", $product_id);
                    $delete_cart_stmt->execute();
                } else {
                    // Update quantity in the cart
                    $update_cart_stmt = $conn->prepare("
                        UPDATE cart 
                        SET quantity = quantity - ? 
                        WHERE product_id = ? AND quantity > 0
                    ");
                    $update_cart_stmt->bind_param("ii", $quantity, $product_id);
                    $update_cart_stmt->execute();
                }
            }
        }

        // Order success
        showAlert('Success', 'Your order has been placed successfully!', 'success');
    } else {
        showAlert('Error', 'Missing required form data.', 'error');
    }
} else {
    showAlert('Error', 'Invalid request method.', 'error');
}
?>
