<?php
session_start();
$username = $_SESSION['email'];
require '../connection.php';

try {
    $stmt = $conn->prepare("SELECT message, created_at, id FROM notifications WHERE username = ? order by id desc");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }

    if (!empty($notifications)) {
        $updateStmt = $conn->prepare("UPDATE notifications SET is_read = 1 WHERE username = ? AND is_read = 0");
        $updateStmt->bind_param("s", $username);
        $updateStmt->execute();
    }

    echo json_encode($notifications);
} catch (Exception $e) {
    echo json_encode(['error' => 'An error occurred while fetching notifications']);
}
?>
