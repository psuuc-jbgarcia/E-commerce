<?php
// Include SweetAlert2 JS for alerts to work
echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';

// Include connection file
require '../connection.php';

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encrypt password

// Check if email already exists
$sql_check = "SELECT * FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email already registered!',
            }).then(() => {
                window.location.href = 'register.html';
            });
        });
    </script>";
    exit();
}

// Insert user data
$sql = "INSERT INTO users (name, email, phone, address, password) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $name, $email, $phone, $address, $password);

if ($stmt->execute()) {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Registration successful!',
            }).then(() => {
                window.location.href = 'login.php';
            });
        });
    </script>";
} else {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Something went wrong. Try again later.',
            }).then(() => {
                window.location.href = 'register.php';
            });
        });
    </script>";
}

$conn->close();
?>
