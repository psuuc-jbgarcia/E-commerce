<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Small Grocery Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="./static/css/style.css">

    <style>
        :root {
            --primary-color: #7D3C98;
            --text-color: #333333;
            --background-color: #FFFFFF;
            --accent-color: #F4D03F;
            --error-color: #E74C3C;
        }

        body {
            background-color: var(--background-color);
            color: var(--text-color);
            font-family: 'Poppins', sans-serif;
        }

        .navbar {
            background-color: var(--primary-color) !important;
            padding: 1.2rem 0;
            animation: fadeInDown 1.2s ease-in-out;
        }

        @keyframes fadeInDown {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .navbar-brand {
            color: #fff !important;
            font-size: 2rem;
        }

        .navbar-nav .nav-link {
            color: #fff !important;
            font-size: 1.1rem;
            margin-right: 1.2rem;
            transition: color 0.3s ease-in-out;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        .btn-primary {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            transition: background-color 0.3s ease-in-out, transform 0.2s;
        }

        .btn-primary:hover {
            background-color: #e1b800 !important;
            transform: translateY(-3px);
        }

        .hero {
            background: linear-gradient(to right, #7D3C98, #F4D03F);
            color: #fff;
            padding: 8rem 0;
            animation: fadeIn 1.5s ease-in-out;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .hero h1 {
            font-size: 4rem;
            animation: slideInDown 1s ease-in-out;
        }

        @keyframes slideInDown {
            0% {
                transform: translateY(-50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 12px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-title {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        #products, #inventory, #faq, #about, #contact {
            background-color: #fff;
            padding: 5rem 0;
            animation: fadeInUp 1.2s ease-in-out;
        }

        @keyframes fadeInUp {
            0% {
                transform: translateY(50px);
                opacity: 0;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .footer {
    background-color: #7D3C98;
    color: #FFFFFF;
    text-align: center;
    padding: 10px 0;
    margin-top: 30px;
    position: fixed;
    bottom: 0;
    width: 100%;
    left: 0;
}
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-store me-2"></i> Small Grocery Shop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="#home"><i class="fas fa-home me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products"><i class="fas fa-list me-1"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#inventory"><i class="fas fa-box me-1"></i> Inventory</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about"><i class="fas fa-info-circle me-1"></i> About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq"><i class="fas fa-question-circle me-1"></i> FAQ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact"><i class="fas fa-envelope me-1"></i> Contact</a></li>
                </ul>
                <a href="./authentication/login.php" class="btn btn-primary ms-3"><i class="fas fa-sign-in-alt me-1"></i> Get
                    Started</a>
            </div>
        </div>
    </nav>

    <section id="home" class="hero text-center">
        <div class="container">
            <h1 class="fw-bold"><i class="fas fa-shopping-bag me-2"></i> Manage Your Grocery Store Online</h1>
            <p class="lead">Track inventory, record sales, and ensure smooth operations with ease.</p>
            <a href="./authentication/login.php" class="btn btn-primary btn-lg mt-3"><i class="fas fa-eye me-1"></i> Explore Products</a>
        </div>
    </section>

    <section id="products" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold mb-4"><i class="fas fa-list me-2"></i> Our Products</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-apple-alt me-1 text-primary"></i> Fresh Produce</h5>
                            <p class="card-text">Quality fruits and vegetables delivered fresh to your store.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-bread-slice me-1 text-success"></i> Baked Goods</h5>
                            <p class="card-text">Freshly baked bread, pastries, and other goods.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-box me-1 text-warning"></i> Packaged Items</h5>
                            <p class="card-text">Various packaged and ready-to-sell products.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="inventory" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold mb-4"><i class="fas fa-boxes me-2"></i> Inventory Management</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-chart-bar me-1 text-primary"></i> Track Stock Levels</h5>
                            <p class="card-text">Keep track of stock levels and avoid overstocking or running out.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-truck-loading me-1 text-success"></i> Receive New Shipments</h5>
                            <p class="card-text">Log incoming shipments and keep your inventory updated.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><i class="fas fa-cogs me-1 text-warning"></i> Automate Restocking</h5>
                            <p class="card-text">Set automatic reorder levels to ensure constant supply.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="faq" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-question-circle me-2"></i> Frequently Asked Questions</h2>
            <div class="row g-4 mt-4">
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">How can I manage my inventory?</h5>
                            <p class="card-text">Our system offers an intuitive interface to update, track, and manage inventory effortlessly.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Is my data secure?</h5>
                            <p class="card-text">Yes, we use advanced encryption to ensure your data is always protected.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="about" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-info-circle me-2"></i> About Us</h2>
            <p class="lead">Our mission is to help small grocery stores manage inventory efficiently, saving time and reducing errors.</p>
        </div>
    </section>

    <section id="contact" class="py-5 text-center">
        <div class="container">
            <h2 class="fw-bold"><i class="fas fa-envelope me-2"></i> Contact Us</h2>
            <p class="lead">Have questions? Reach out to us anytime, and we will be happy to assist you.</p>
        </div>
    </section>

    <footer class="footer text-center">
        <p>&copy; 2025 Small Grocery Shop. All Rights Reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="./static/js/script.js"></script>
</body>

</html>
