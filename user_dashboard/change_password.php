<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<?php
session_start();
require '../connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'All fields are required!',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='profile.php';
                });
              </script>";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Password Mismatch!',
                    text: 'New password and confirm password do not match.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='profile.php';
                });
              </script>";
        exit();
    }

    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!password_verify($current_password, $user['password'])) {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Incorrect Password!',
                    text: 'Your current password is incorrect.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='profile.php';
                });
              </script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE users SET password = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Password Changed!',
                    text: 'Your password has been updated successfully.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='profile.php';
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed!',
                    text: 'There was an issue updating your password. Please try again later.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href='profile.php';
                });
              </script>";
    }
}
?>

</body>
</html>