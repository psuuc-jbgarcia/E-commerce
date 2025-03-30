<?php
session_start();
require '../connection.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!$data || !isset($data['item_id']) || !isset($data['action']) || !isset($data['quantity'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit();
}

$item_id = intval($data['item_id']);
$action = $data['action'];
$quantity = intval($data['quantity']);

$stmt = $conn->prepare("SELECT quantity FROM cart WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $currentQuantity = $row['quantity'];

    if ($action === 'increase') {
        $currentQuantity++;
    } elseif ($action === 'decrease' && $currentQuantity > 0) {
        $currentQuantity--;
    }

    if ($currentQuantity === 0) {
        $delete_stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $delete_stmt->bind_param("i", $item_id);
        if ($delete_stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Item removed']);
            exit();
        }
    } else {
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE id = ?");
        $update_stmt->bind_param("ii", $currentQuantity, $item_id);
        if ($update_stmt->execute()) {
            echo json_encode(['success' => true, 'quantity' => $currentQuantity]);
            exit();
        }
    }
}

echo json_encode(['success' => false, 'error' => 'Failed to update quantity']);
?>
