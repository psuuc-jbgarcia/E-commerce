<?php
session_start();
require '../connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['item_id']) || !isset($data['action'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

$item_id = intval($data['item_id']);
$action = $data['action'];

// Check if item exists in the cart
$stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $quantity = $row['quantity'];

    if ($action === 'increase') {
        $quantity++;
    } elseif ($action === 'decrease' && $quantity > 1) {
        $quantity--;
    }

    // Update quantity in the cart
    $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
    $update_stmt->bind_param("ii", $quantity, $item_id);
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'quantity' => $quantity]);
        exit();
    }
}

// If something goes wrong
echo json_encode(['success' => false, 'error' => 'Failed to update quantity']);
?>
