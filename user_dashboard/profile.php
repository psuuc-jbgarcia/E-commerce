<?php
session_start();
require '../connection.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); 
    exit();
}

$user_id = $_SESSION['user_id']; 

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        .profile-page {
            background-color: #7D3C98; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding-top: 50px;
        }
        .profile-card {
            background: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
            text-align: center;
        }
        .profile-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #7D3C98;
            color: #fff;
            font-size: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        body {
            background-color: #5d346b !important;
        }
    </style>
</head>
<body class="profile-page">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<div class="container">
    <?php include 'navigation.php'; ?>

    <div class="profile-card mx-auto">
        <div class="profile-icon">
            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
        </div>
        <h4 class="text-primary fw-bold">Profile Page</h4>
        <p class="text-muted">Manage your account details here</p>

        <?php if (isset($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <!-- Profile Form -->
        <form action="update_profile.php" method="POST">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required disabled>
                </div>
                <div class="col-md-6">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['address']; ?>" required>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Update Profile</button>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                    Change Password
                </button>
            </div>
        </form>

   <!-- Secure PIN Button -->
<div class="col-md-6 mt-4 mx-auto">
    <label class="form-label">Secure Checkout PIN</label>
    <?php if (is_null($user['secure_checkout_pin'])): ?>
        <button class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#setPinModal">
            Set Up Secure PIN
        </button>
    <?php else: ?>
        <input type="text" class="form-control text-center fw-bold" 
            value="<?php echo substr($user['secure_checkout_pin'], 0, 1) . '***'; ?>" 
            disabled>
        <button class="btn btn-primary w-100 mt-2" data-bs-toggle="modal" data-bs-target="#setPinModal">
            Update Secure PIN
        </button>
    <?php endif; ?>
</div>


<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changePasswordModalLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="changePasswordForm" action="change_password.php" method="post">
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            <button class="btn btn-outline-secondary toggle-password" type="button">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="setPinModal" tabindex="-1" aria-labelledby="setPinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="setPinModalLabel">Set Secure Checkout PIN</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="set_secure_pin.php" method="post">
                    <div class="mb-3">
                        <label for="new_pin" class="form-label">Enter 4-Digit PIN</label>
                        <input type="password" class="form-control text-center" id="new_pin" name="new_pin" maxlength="4" pattern="\d{4}" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Save PIN</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
document.querySelectorAll('.toggle-password').forEach(button => {
    button.addEventListener('click', function () {
        let input = this.parentElement.querySelector('input'); // Get the input field
        let icon = this.querySelector('i'); // Get the icon

        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
