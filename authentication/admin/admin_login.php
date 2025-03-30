<?php
echo '   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
';
session_start();
require "../../connection.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $query = "SELECT * FROM adminacc WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if ($password === $admin['password']) { 
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['username'] = $admin['username'];
            echo '<script>
                setTimeout(function() {
                    Swal.fire({
                        icon: "success",
                        title: "Login Successful!",
                        text: "Redirecting to Admin Dashboard...",
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        window.location.href = "../../admin_dashboard/admin_dashboard.php";
                    });
                }, 100);
            </script>';
        } else {
            echo '<script>
                setTimeout(function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: "Incorrect password.",
                        showConfirmButton: true
                    }).then(function() {
                        window.location.href = "../../admin.php";
                    });
                }, 100);
            </script>';
        }
    } else {
        echo '<script>
            setTimeout(function() {
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "User not found.",
                    showConfirmButton: true
                }).then(function() {
                        window.location.href = "../../admin.php";
                    });
            }, 100);
        </script>';
    }
}
?>
