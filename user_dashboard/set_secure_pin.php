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
require '../connection.php';


if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

$user_id = $_SESSION['user_id'];
$new_pin = $_POST['new_pin'] ?? '';
$_SESSION['pin'] =$new_pin;
if (!preg_match("/^\d{4}$/", $new_pin)) {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid PIN',
            text: 'PIN must be exactly 4 digits!',
            confirmButtonColor: '#d33'
        }).then(() => {
            window.history.back();
        });
    </script>";
    exit;
}

$stmt = $conn->prepare("UPDATE users SET secure_checkout_pin = ? WHERE id = ?");
$stmt->bind_param("si", $new_pin, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'PIN Updated',
        text: 'Your secure PIN has been successfully updated!',
        confirmButtonColor: '#28a745'
    }).then(() => {
        window.location.href = 'profile.php';
    });
</script>";
exit;
?>

</body>
</html>