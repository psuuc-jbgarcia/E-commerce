<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #7D3C98; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand fw-bold" href="dashboard.php" style="color: #F4D03F;">
            <i class="fas fa-store me-1"></i> Small Shop
        </a>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="dashboard.php" style="color: #FFFFFF;"><i class="fas fa-home me-1"></i> Home</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php" style="color: #FFFFFF;"><i class="fas fa-shopping-cart me-1"></i> Cart</a></li>
                <li class="nav-item"><a class="nav-link" href="orders.php" style="color: #FFFFFF;"><i class="fas fa-box me-1"></i>My Orders</a></li>
                <li class="nav-item"><a class="nav-link" href="track_order.php" style="color: #FFFFFF;"><i class="fas fa-map-marker-alt me-1"></i>Track Orders</a></li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <button class="btn btn-outline-light position-relative me-3" style="border-color: #F4D03F; color: #F4D03F;">
                <i class="fas fa-bell"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    3
                </span>
            </button>

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

<!-- Add a margin-top to the content below the navbar to prevent it from being hidden -->
<div style="margin-top: 80px;"> <!-- Adjust this value based on your navbar height -->
    <!-- Your page content goes here -->
</div>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">