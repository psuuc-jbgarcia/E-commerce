<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}

require '../connection.php';
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];
    $new_category = isset($_POST['new_category']) ? $_POST['new_category'] : '';

    // Handle new category if it's selected
    if ($category === 'new' && !empty($new_category)) {
        $category = $new_category;
        // Insert the new category into the database
        $category_insert = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $category_insert->bind_param("s", $category);
        $category_insert->execute();
    }

    // Handle image upload
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $image_name = $_FILES['product_image']['name'];
        $image_tmp_name = $_FILES['product_image']['tmp_name'];
        $image_size = $_FILES['product_image']['size'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // Generate a unique name for the image to avoid overwriting
        $image_new_name = uniqid('', true) . '.' . $image_extension;

        // Set the upload directory
        $upload_dir = '../uploads/';
        $image_path = $upload_dir . $image_new_name;

        // Move the uploaded file to the upload directory
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            // Prepare and execute the INSERT query
            $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_name, stock, category) VALUES (?, ?, ?, ?, ?, ?)");
            if (!$stmt) {
                die("Error preparing statement: " . $conn->error);
            }

            // Bind the parameters (correct data types)
            $stmt->bind_param("ssdssi", $name, $description, $price, $image_new_name, $stock, $category);

            // Execute the query
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
                exit();
            } else {
                echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to add product.',
                            confirmButtonText: 'OK'
                        });
                      </script>";
                exit();
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
            exit();
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
        exit();
    }
}
?>
