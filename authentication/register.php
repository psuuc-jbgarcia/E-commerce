<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Golden Mart Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            background-color: rgb(66, 36, 78);
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
            width: 90%;
            max-width: 800px;
        }

        .form-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .logo {
            width: 120px;
            display: block;
            margin: 0 auto 10px;
        }

        .system-title {
            text-align: center;
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

        .form-control:focus {
            border-color: #7D3C98;
            box-shadow: 0 0 8px rgba(125, 60, 152, 0.5);
        }

        .input-group-text {
            cursor: pointer;
            background-color: #7D3C98;
            color: #FFFFFF;
        }

        @media (max-width: 768px) {
            .form-content {
                grid-template-columns: 1fr;
            }
        }

        .text-danger {
            font-size: 0.875rem;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <img src="https://cdn-icons-png.flaticon.com/512/869/869869.png" alt="Shop Logo" class="logo">
        <div class="system-title">Golden Mart Inventory</div>

        <form id="registerForm" action="register_user.php" method="POST" onsubmit="return validateForm()">
            <div class="form-content">
                <div class="mb-3">
                    <input type="text" class="form-control" id="name" name="name" placeholder="Full Name">
                    <small class="text-danger" id="nameError"></small>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email Address">
                    <small class="text-danger" id="emailError"></small>
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone Number">
                    <small class="text-danger" id="phoneError"></small>
                </div>
                <div class="mb-3">
                    <textarea class="form-control" id="address" name="address" rows="2" placeholder="Address"></textarea>
                    <small class="text-danger" id="addressError"></small>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <span class="input-group-text" onclick="togglePassword('password', 'eyeIcon')">
                            <i id="eyeIcon" class="fas fa-eye"></i>
                        </span>
                    </div>
                    <small class="text-danger" id="passwordError"></small>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                            placeholder="Confirm Password">
                        <span class="input-group-text" onclick="togglePassword('confirm_password', 'eyeIconConfirm')">
                            <i id="eyeIconConfirm" class="fas fa-eye"></i>
                        </span>
                    </div>
                    <small class="text-danger" id="confirmPasswordError"></small>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 mt-3"><i class="fas fa-user-plus me-1"></i> Register</button>
            <p class="text-center mt-3">Already have an account? <a href="login.php" style="color: #7D3C98;">Login</a></p>
        </form>
    </div>

    <script>
        function validateForm() {
            let valid = true;

            document.getElementById("nameError").innerText = "";
            document.getElementById("emailError").innerText = "";
            document.getElementById("phoneError").innerText = "";
            document.getElementById("addressError").innerText = "";
            document.getElementById("passwordError").innerText = "";
            document.getElementById("confirmPasswordError").innerText = "";

            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const address = document.getElementById('address').value.trim();
            const password = document.getElementById('password').value.trim();
            const confirm_password = document.getElementById('confirm_password').value.trim();

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phoneRegex = /^[0-9]{10,11}$/;

            if (name === '') {
                document.getElementById("nameError").innerText = "Full Name is required.";
                valid = false;
            }
            if (email === '') {
                document.getElementById("emailError").innerText = "Email Address is required.";
                valid = false;
            } else if (!emailRegex.test(email)) {
                document.getElementById("emailError").innerText = "Invalid email format.";
                valid = false;
            }
            if (phone === '') {
                document.getElementById("phoneError").innerText = "Phone Number is required.";
                valid = false;
            } else if (!phoneRegex.test(phone)) {
                document.getElementById("phoneError").innerText = "Phone number must be 10-11 digits.";
                valid = false;
            }
            if (address === '') {
                document.getElementById("addressError").innerText = "Address is required.";
                valid = false;
            }
            if (password === '') {
                document.getElementById("passwordError").innerText = "Password is required.";
                valid = false;
            } else if (password.length < 6) {
                document.getElementById("passwordError").innerText = "Password must be at least 6 characters.";
                valid = false;
            }
            if (confirm_password === '') {
                document.getElementById("confirmPasswordError").innerText = "Confirm Password is required.";
                valid = false;
            } else if (password !== confirm_password) {
                document.getElementById("confirmPasswordError").innerText = "Passwords do not match.";
                valid = false;
            }
            return valid;
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
