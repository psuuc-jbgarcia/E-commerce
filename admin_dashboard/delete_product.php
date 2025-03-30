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

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    $stmt = $conn->prepare("SELECT image_name FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($image_name);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $stmt_delete = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt_delete->bind_param("i", $product_id);

        if ($stmt_delete->execute()) {
            $image_path = '../uploads/' . $image_name;
            if (file_exists($image_path)) {
                unlink($image_path);
            }

            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Product Deleted',
                        text: 'Product has been successfully deleted!',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'manage_products.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete product. Please try again.',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Not Found',
                    text: 'Product not found.',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manage_products.php';
                    }
                });
              </script>";
    }
} else {
    echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Invalid Request',
                text: 'No product ID provided.',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'manage_products.php';
                }
            });
          </script>";
}
?>

</body>
</html>