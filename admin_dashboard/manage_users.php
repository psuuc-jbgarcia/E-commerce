
<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - Golden Mart Inventory</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../static/css/admin.css">
    <style>
        .secondary-nav {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #ddd;
            padding: 10px 20px;
            z-index: 1000;
        }

        .secondary-nav .btn-outline-secondary {
            border-color: #7D3C98;
            color: #7D3C98;
        }

        .secondary-nav .btn-outline-secondary:hover {
            background-color: #7D3C98;
            color: #fff;
        }

        .secondary-nav .navbar-text {
            font-size: 1rem;
            color: #333;
        }

        .btn-outline-secondary {
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-outline-secondary:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <div class="wrapper">
    <?php include 'admin_nav.php'; ?>


        <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm secondary-nav">
                <button type="button" id="sidebarCollapse" class="btn btn-outline-secondary">
                    <i class="fas fa-bars"></i>
                </button>
                <h1>This page is static data only; it is under development.</h1>
                <div class="ms-auto">
                    <span class="navbar-text me-3">
                        <i class="fas fa-user-shield me-1"></i> Admin: <?php echo $_SESSION['username']; ?>
                    </span>
                </div>
            </nav>

            <div class="container mt-4">
                <h2 class="mb-4"><i class="fas fa-users me-2"></i> Manage Users</h2>
                <button class="btn btn-primary mb-3"><i class="fas fa-user-plus me-1"></i> Add User</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>John Doe</td>
                            <td>john@example.com</td>
                            <td>Admin</td>
                            <td>
                                <button class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/script.js"></script>
</body>

</html>
