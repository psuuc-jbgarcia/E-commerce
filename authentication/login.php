<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Small Shop Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: rgb(66, 36, 78);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container-wrapper {
            background-color: #FFFFFF;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
        }

        .left-side {
            background-color: #7D3C98;
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .hero-image {
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
        }

        .right-side {
            padding: 40px;
        }

    

        .shop-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #7D3C98;
            margin-bottom: 20px;
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

        .text-danger {
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .left-side {
                border-radius: 12px 12px 0 0;
                padding: 20px 0;
            }

            .container-wrapper {
                flex-direction: column;
            }

            .right-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container-wrapper d-flex">
        <!-- Left Side - Image -->
        <div class="left-side col-md-6 d-none d-md-flex">
            <img src="../static/images/hero.png" alt="Small Store" class="hero-image">
        </div>

        <!-- Right Side - Login Form -->
        <div class="right-side col-md-6">
            <div class="text-center">
            <img src="" alt="Shop Logo" class="logo">
            <p class="shop-title">Small Shop</p>
                <h3 class="fw-bold mb-4" style="color: #7D3C98;"> Login</h3>
            </div>
            <form id="loginForm" action="logingin.php" method="POST" onsubmit="return validateForm(event)">
                <div class="mb-3 text-start">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                    <small class="text-danger" id="emailError"></small>
                </div>
                <div class="mb-3 text-start">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <span class="input-group-text" onclick="togglePassword('password', 'eyeIcon')">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </span>
                    </div>
                    <small class="text-danger" id="passwordError"></small>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-sign-in-alt me-1"></i> Login</button>
            </form>
            <p class="text-center">Don't have an account? <a href="register.php">Register</a></p>
        </div>
    </div>

    <script>
        function validateForm(event) {
            event.preventDefault();
            let valid = true;

            document.getElementById("emailError").innerText = "";
            document.getElementById("passwordError").innerText = "";

            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email === '') {
                document.getElementById("emailError").innerText = "Email is required.";
                valid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById("emailError").innerText = "Invalid email format.";
                valid = false;
            }

            if (password === '') {
                document.getElementById("passwordError").innerText = "Password is required.";
                valid = false;
            } else if (password.length < 6) {
                document.getElementById("passwordError").innerText = "Password must be at least 6 characters.";
                valid = false;
            }

            if (valid) {
                document.getElementById('loginForm').submit();
            }
        }

        function togglePassword(fieldId, eyeIconId) {
            const passwordField = document.getElementById(fieldId);
            const eyeIcon = document.getElementById(eyeIconId);
            passwordField.type = passwordField.type === "password" ? "text" : "password";
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        }
    </script>
</body>

</html>
