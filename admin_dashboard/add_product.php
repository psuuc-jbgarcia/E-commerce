<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $new_category = isset($_POST['new_category']) ? $_POST['new_category'] : '';
    $created_at = date('Y-m-d H:i:s'); // Automatically set current timestamp

    // Check if "Add New Category" is selected
    if ($category === 'new' && !empty($new_category)) {
        $category = $new_category; // Use new category
    }

    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $image_size = $_FILES['product_image']['size'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array(strtolower($image_extension), $allowed_extensions)) {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Invalid File Type',
                        text: 'Only JPG, JPEG, PNG, and GIF files are allowed.',
                        confirmButtonText: 'OK'
                    });
                  </script>";
            exit();
        }

        $image_new_name = uniqid('', true) . '.' . $image_extension;
        $upload_dir = '../uploads/';
        $image_path = $upload_dir . $image_new_name;

        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_name, stock, category, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            $stmt->bind_param("ssdssss", $name, $description, $price, $image_new_name, $stock, $category, $created_at);

            if ($stmt->execute()) {

                echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Product Added',
                            text: 'Product has been successfully added!',
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
                            text: 'Failed to add product. Please try again.',
                            confirmButtonText: 'OK'
                        });
                      </script>";
            }
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'File upload failed. Please try again.',
                        confirmButtonText: 'OK'
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'No Image',
                    text: 'No image uploaded. Please select an image.',
                    confirmButtonText: 'OK'
                });
              </script>";
    }
}
?>

</body>

</html>
