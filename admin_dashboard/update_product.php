<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}

require '../connection.php';

if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category']; 
    $new_category = isset($_POST['new_category']) ? $_POST['new_category'] : ''; 
    $image_name = $_FILES['product_image']['name'];

    if ($category === 'new' && $new_category !== '') {
        $category = $new_category;  
    }

    if ($image_name) {
        $image_path = '../uploads/' . $image_name;
        move_uploaded_file($_FILES['product_image']['tmp_name'], $image_path);
    } else {
        $stmt = $conn->prepare("SELECT image_name FROM products WHERE id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($image_name);
        $stmt->fetch();
    }

    $stmt_update = $conn->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category = ?, image_name = ? WHERE id = ?");
    $stmt_update->bind_param("ssdissi", $name, $description, $price, $stock, $category, $image_name, $product_id);

    if ($stmt_update->execute()) {
        echo "<script>
                Swal.fire({
                    title: 'Success',
                    text: 'Product updated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(function() {
                 window.location.href = 'manage_products.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to update product',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(function() {
                    window.location.href = 'manage_products.php?error=Failed to update product';
                });
              </script>";
    }


}
?>
</body>
</html>
