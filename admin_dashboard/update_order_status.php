<?php
require '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $order_status = $_POST['order_status'];

    // Fetch order details
    $stmt = $conn->prepare("SELECT username, product_names FROM orders WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $stmt->bind_result($username, $product_names);
    $stmt->fetch();
    $stmt->close();

    if (!$username) {
        http_response_code(400);
        echo "Invalid Order ID";
        exit();
    }

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param("si", $order_status, $order_id);

    if ($stmt->execute()) {
        // Insert into notifications table with product details
        $message = "Your order (ID: $order_id) containing '$product_names' has been updated to '$order_status'.";
        $notif_stmt = $conn->prepare("INSERT INTO notifications (username, order_id, message) VALUES (?, ?, ?)");
        $notif_stmt->bind_param("sis", $username, $order_id, $message);
        $notif_stmt->execute();
        $notif_stmt->close();

        echo "Success";
    } else {
        http_response_code(500);
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
