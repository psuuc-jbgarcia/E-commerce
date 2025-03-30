<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Golden Mart Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
   <link rel="stylesheet" href="./static/css/admin.css">

</head>

<body>
    <div class="container-wrapper">
        <div class="floating-bubble bubble-1"></div>
        <div class="floating-bubble bubble-2"></div>

        <img src="../static/images/admin_logo.png" alt="Admin Logo" class="logo">
        <div class="title"><i class="fas fa-user-shield me-1"></i> Admin Portal</div>

        <form id="adminLoginForm" action="./authentication/admin/admin_login.php" method="POST" onsubmit="return validateForm()">
            <div class="mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Admin Email">
                <small class="text-danger" id="emailError"></small>
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
            <button type="submit" class="btn btn-primary w-100 mb-3"><i class="fas fa-lock me-1"></i> Secure Login</button>
            <!-- <p class="text-center"><a href="forgot_password.php">Forgot Password?</a></p> -->
        </form>
    </div>

    <script>
        function validateForm() {
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
