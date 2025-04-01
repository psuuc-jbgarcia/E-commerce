<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<style>
#sidebar {
    width: 250px;
    background-color: #7D3C98;
    transition: all 0.3s;
    min-height: 100vh;
    padding-top: 20px;
}

#sidebar .sidebar-header {
    padding: 20px;
    background: #5B2C6F;
}

#sidebar ul.components {
    padding: 20px 0;
}

#sidebar ul li a {
    padding: 10px 20px;
    font-size: 1rem;
    display: block;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
}

#sidebar ul li a:hover,
#sidebar ul li a.active {
    background-color: #F4D03F;
    color: #333;
}

.logo {
    width: 80px;
    margin-bottom: 10px;
}
</style>

<nav id="sidebar" class="bg-purple">
    <div class="sidebar-header text-center">
    <img src="../static/images/logo.png" alt="Admin Logo" class="logo mx-auto d-block" 
     style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover;">
        <h4 class="text-white">Simple Shop</h4>
    </div>
    <ul class="list-unstyled components">
        <li><a href="admin_dashboard.php" class="<?= $current_page == 'admin_dashboard.php' ? 'active' : '' ?>"><i class="fas fa-home me-2"></i> Home</a></li>
        <li><a href="manage_products.php" class="<?= $current_page == 'manage_products.php' ? 'active' : '' ?>"><i class="fas fa-box me-2"></i> Manage Products</a></li>
        <li><a href="manage_users.php" class="<?= $current_page == 'manage_users.php' ? 'active' : '' ?>"><i class="fas fa-users me-2"></i> Manage Users</a></li>
        <li><a href="manage_orders.php" class="<?= $current_page == 'manage_orders.php' ? 'active' : '' ?>"><i class="fas fa-shopping-cart me-2"></i> Manage Orders</a></li>
        <li><a href="sale_history.php" class="<?= $current_page == 'sale_history.php' ? 'active' : '' ?>"><i class="fas fa-history me-2"></i> Sale History</a></li> <!-- Added Sale History -->
        <li><a href="../authentication/admin/admin_logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
    </ul>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("sidebarCollapse").addEventListener("click", function () {
        document.getElementById("sidebar").classList.toggle("active");
    });
});
</script>
