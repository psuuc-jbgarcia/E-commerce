<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../authentication/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    $delete_sql = "DELETE FROM cart WHERE id = ? AND user_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("ii", $cart_id, $user_id);
    
    if ($delete_stmt->execute()) {
        header("Location: cart.php"); // Ensure you're redirected back to the cart page
        exit();
    } else {
        echo "Error deleting item.";
    }
} else {
    echo "Invalid request.";
}
?>
