<?php
require '../connection.php';

$email = $_POST['email'];
$password = $_POST['password'];

$sql_check = "SELECT * FROM users WHERE email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $user = $result_check->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['number'] = $user['phone'];
        $_SESSION['address'] = $user['address'];

        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Login Successful!',
                        text: 'Welcome, " . $user['name'] . "!',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = '../user_dashboard/dashboard.php';
                    });
                });
            </script>
        </body>
        </html>";
        exit();
    } else {
        echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed!',
                        text: 'Incorrect email or password!',
                    }).then(() => {
                        window.location.href = 'login.php';
                    });
                });
            </script>
        </body>
        </html>";
        exit();
    }
} else {
    echo "
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Login Failed!',
                    text: 'Email not registered!',
                }).then(() => {
                    window.location.href = 'login.php';
                });
            });
        </script>
    </body>
    </html>";
    exit();
}

$conn->close();
?>
