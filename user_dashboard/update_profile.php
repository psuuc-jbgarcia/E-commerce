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
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (empty($name) || empty($phone) || empty($address)) {
        $error_msg = "All fields are required.";
    } else {
        $sql = "UPDATE users SET name = ?, phone = ?, address = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $phone, $address, $user_id);

        if ($stmt->execute()) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated!',
                        text: 'Your profile has been successfully updated.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'profile.php'; // Redirect back to profile page
                    });
                  </script>";
            exit();
        } else {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed!',
                        text: 'There was an issue updating your profile. Please try again later.',
                        confirmButtonText: 'OK'
                    }).then(function() {
                        window.location.href = 'profile.php'; // Redirect back to profile page
                    });
                  </script>";
            exit();
        }
    }
}
?>

</body>
</html>