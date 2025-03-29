<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Small Shop Inventory</title>
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
            background-color: #7D3C98;
            border: none;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background-color: #F4D03F;
            color: #333333;
        }

        .btn-warning {
            background-color: #F4D03F;
            color: #333333;
        }

        .btn-warning:hover {
            background-color: #E0A800;
        }

        .input-group-text {
            cursor: pointer;
            background-color: #7D3C98;
            color: #FFFFFF;
        }

        .form-control:focus {
            border-color: #7D3C98;
            box-shadow: 0 0 8px rgba(125, 60, 152, 0.5);
        }

        a {
            color: #7D3C98;
        }

        a:hover {
            color: #F4D03F;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <img src="https://via.placeholder.com/100" alt="Logo" class="logo">
        <h3 class="text-center fw-bold mb-4" style="color: #7D3C98;"><i class="fas fa-sign-in-alt me-1"></i> Login</h3>
        <form id="loginForm" action="logingin.php" method="POST" onsubmit="return validateForm(event)">
            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
            </div>
            <div class="mb-3">
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                    <span class="input-group-text" onclick="togglePassword('password', 'eyeIcon')">
                        <i id="eyeIcon" class="fas fa-eye"></i>
                    </span>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-sign-in-alt me-1"></i> Login</button>
        </form>
        <p class="text-center">Don't have an account? <a href="register.php">Register</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateForm(event) {
            event.preventDefault();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            if (email === '') {
                showSweetAlert('error', 'Email Required!', 'Please enter your email.');
                return false;
            }
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showSweetAlert('error', 'Invalid Email!', 'Enter a valid email address.');
                return false;
            }
            if (password === '') {
                showSweetAlert('error', 'Password Required!', 'Please enter your password.');
                return false;
            }
            if (password.length < 6) {
                showSweetAlert('error', 'Weak Password!', 'Password must be at least 6 characters.');
                return false;
            }
            document.getElementById('loginForm').submit();
        }

        function showSweetAlert(icon, title, text) {
            Swal.fire({
                icon: icon,
                title: title,
                text: text,
                confirmButtonText: 'OK'
            });
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
