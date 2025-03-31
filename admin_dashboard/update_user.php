
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
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
    $id = intval($_POST['id']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $address = htmlspecialchars($_POST['address']);

    $update_sql = "UPDATE users SET name='$name', email='$email', phone='$phone', address='$address' WHERE id=$id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'User updated successfully.'
                    }).then(function() {
                        window.location.href = 'manage_users.php';
                    });
                };
              </script>";
    } else {
        echo "<script>
                window.onload = function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Error updating record: " . $conn->error . "'
                    }).then(function() {
                        window.location.href = 'manage_users.php';
                    });
                };
              </script>";
    }
    exit();
}
?>
</body>
</html>
