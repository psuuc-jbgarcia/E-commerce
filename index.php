<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Shop</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./static/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-lg">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-store text-primary me-2"></i> Small Shop Inventory
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#home"><i class="fas fa-home me-1"></i> Home</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="#features"><i class="fas fa-list me-1"></i>
                            Features</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i>
                            About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services"><i class="fas fa-concierge-bell me-1"></i>
                            Services</a></li>
                </ul>
                <a href="./authentication/login.php" class="btn btn-primary ms-3"><i class="fas fa-sign-in-alt me-1"></i> Get Started</a>
                </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero bg-light py-5 text-center">
        <div class="container">
            <h1 class="display-4 fw-bold"><i class="fas fa-cogs me-2"></i> Manage Your Inventory with Ease</h1>
            <p class="lead">Track stock levels, record sales, and reduce errors efficiently.</p>
            <a href="./authentication/login.php" class="btn btn-primary btn-lg mt-3"><i class="fas fa-play-circle me-1"></i> Try Now</a>
            </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container text-center">
            <h2 class="fw-bold mb-4"><i class="fas fa-star me-2"></i> Key Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-boxes me-1 text-primary"></i> Inventory Management</h5>
                            <p class="card-text">Add, update, and remove items effortlessly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-calculator me-1 text-success"></i> Automated Sales
                                Calculation</h5>
                            <p class="card-text">Automatically calculate total sales with accuracy.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-history me-1 text-warning"></i> Sales History Management
                            </h5>
                            <p class="card-text">Track and review sales records in real-time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-info-circle me-2"></i> About Us</h2>
            <p class="lead">Our mission is to help small grocery stores manage inventory efficiently, saving time and reducing
                errors.</p>
        </div>
    </section>

    <!-- Info Section -->
    <section id="info" class="py-5 bg-white text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-info-circle me-2"></i> Why Choose Us?</h2>
            <p class="lead">Our system is specifically designed to cater to the needs of small grocery stores. It helps manage
                inventory, monitor sales, and automate processes to ensure smooth business operations.</p>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-user-check me-1 text-primary"></i> User-Friendly Interface</h5>
                            <p class="card-text">Easily manage products and orders without any technical expertise.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-pie me-1 text-success"></i> Analytics for Decision Making</h5>
                            <p class="card-text">Gain insights into sales trends and inventory levels to make data-driven decisions.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-lock me-1 text-warning"></i> Secure and Reliable</h5>
                            <p class="card-text">Protects data with secure authentication and ensures system reliability.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-concierge-bell me-2"></i> Our Services</h2>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-sync-alt me-1 text-primary"></i> Real-Time Inventory
                                Updates</h5>
                            <p class="card-text">Keep track of inventory levels with real-time updates to prevent stockouts.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-shipping-fast me-1 text-success"></i> Fast Order
                                Processing</h5>
                            <p class="card-text">Ensure that orders are processed quickly and efficiently.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-line me-1 text-warning"></i> Sales and Analytics</h5>
                            <p class="card-text">Analyze sales trends and make informed business decisions.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark text-white text-center py-3">
        <p>&copy; 2025 Small Shop Inventory. All Rights Reserved.</p>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <!-- Custom JS -->
    <script src="./static/js/script.js"></script>
</body>

</html>
