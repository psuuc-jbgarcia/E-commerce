<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $user_id = $data['user_id'];
    $entered_pin = $data['pin'];

    require '../connection.php';

    // Fetch the stored PIN from the database
    $query = "SELECT secure_checkout_pin FROM users WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($stored_pin);
    $stmt->fetch();
    $stmt->close();

    // Check if the entered PIN matches the stored PIN
    if ($entered_pin === $stored_pin) {
        echo json_encode(['valid' => true]);
    } else {
        echo json_encode(['valid' => false]);
    }
} else {
    echo json_encode(['valid' => false]);
}
?>
