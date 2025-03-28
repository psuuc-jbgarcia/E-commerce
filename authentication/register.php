<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Small Shop Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 500px;
            position: relative;
        }

        .logo {
            width: 100px;
            margin: 0 auto 20px auto;
            display: block;
        }

        .btn-primary {
            background-color: #4A90E2;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #357ABD;
        }

        .btn-warning {
            background-color: #FFC107;
            color: #333333;
        }

        .btn-warning:hover {
            background-color: #E0A800;
        }

        .input-group-text {
            cursor: pointer;
            background-color: #4A90E2;
            color: #FFFFFF;
        }

        .form-control:focus {
            border-color: #4A90E2;
            box-shadow: 0 0 8px rgba(74, 144, 226, 0.5);
        }

        a {
            color: #4A90E2;
        }

        a:hover {
            color: #357ABD;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <img src="https://via.placeholder.com/100" alt="Logo" class="logo">
        <h3 class="text-center fw-bold mb-4" style="color: #4A90E2;"><i class="fas fa-user-plus me-1"></i> Register</h3>
        <form id="registerForm" action="register_user.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Full Name">
            </div>
            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
            </div>
            <div class="mb-3">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
            </div>
            <div class="mb-3">
                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Address"></textarea>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <span class="input-group-text" onclick="togglePassword('password', 'eyeIcon')">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Confirm Password">
                    <span class="input-group-text" onclick="togglePassword('confirm_password', 'eyeIconConfirm')">
                        <i id="eyeIconConfirm" class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-user-plus me-1"></i> Register</button>
        </form>
        <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateForm() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const address = document.getElementById('address').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirm_password = document.getElementById('confirm_password').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9]{10,11}$/;

            if (name === '' || email === '' || phone === '' || address === '' || password === '' || confirm_password === '') {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'All fields are required!' });
                return false;
            }
            if (!emailRegex.test(email)) {
                Swal.fire({ icon: 'error', title: 'Invalid Email!', text: 'Please enter a valid email address.' });
                return false;
            }
            if (!phoneRegex.test(phone)) {
                Swal.fire({ icon: 'error', title: 'Invalid Phone Number!', text: 'Phone number must be 10-11 digits.' });
                return false;
            }
            if (password.length < 6) {
                Swal.fire({ icon: 'error', title: 'Weak Password!', text: 'Password must be at least 6 characters.' });
                return false;
            }
            if (password !== confirm_password) {
                Swal.fire({ icon: 'error', title: 'Password Mismatch!', text: 'Passwords do not match!' });
                return false;
            }
            return true;
        }

        function togglePassword(fieldId, eyeIconId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(eyeIconId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
