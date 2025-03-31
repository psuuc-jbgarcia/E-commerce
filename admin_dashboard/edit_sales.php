<?php
require '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $saleId = intval($_POST["id"]);
    $productName = $_POST["product_name"];
    $quantity = intval($_POST["quantity"]);
    $price = floatval($_POST["price"]);
    $total = $quantity * $price;

    $sql = "UPDATE sales SET product_name = ?, quantity = ?, price = ?, total = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siddi", $productName, $quantity, $price, $total, $saleId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    $conn->close();
}
?>
