<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #7D3C98; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand fw-bold" href="dashboard.php" style="color: #F4D03F;">
            <i class="fas fa-store me-1"></i> Small Shop
        </a>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        <i class="fas fa-home me-1"></i> Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'cart.php') ? 'active' : ''; ?>" href="cart.php">
                        <i class="fas fa-shopping-cart me-1"></i> Cart
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'orders.php') ? 'active' : ''; ?>" href="orders.php">
                        <i class="fas fa-box me-1"></i> My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'track_order.php') ? 'active' : ''; ?>" href="track_order.php">
                        <i class="fas fa-map-marker-alt me-1"></i> Track Orders
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <!-- Notification Button with Modal Trigger -->
            <button class="btn btn-outline-light position-relative me-3" style="border-color: #F4D03F; color: #F4D03F;" data-bs-toggle="modal" data-bs-target="#notifModal">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </button>

            <!-- Profile Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" style="border-color: #F4D03F; color: #F4D03F;" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user"></i> Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php"><i class="fas fa-user-circle me-1"></i> My Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a></li>
                </ul>
            </div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #F4D03F;">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</nav>

<!-- Add margin-top to prevent content overlap -->
<div style="margin-top: 80px;">
    <!-- Page content goes here -->
</div>

<!-- Notification Modal -->
<div class="modal fade" id="notifModal" tabindex="-1" aria-labelledby="notifModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #7D3C98; color: #fff;">
                <h5 class="modal-title" id="notifModalLabel"><i class="fas fa-bell me-2"></i> Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p><i class="fas fa-tools fa-3x text-warning mb-3"></i></p>
                <h5>This feature is under development.</h5>
                <p class="text-muted">You will be able to receive notifications regarding order status soon.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles for Active Menu -->
<style>
    .nav-link.active {
        background-color: #F4D03F !important;
        color: #333333 !important;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

<!-- Bootstrap CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
