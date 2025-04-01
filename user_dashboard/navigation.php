<?php
$current_page = basename($_SERVER['PHP_SELF']);
$username = $_SESSION['email'];
require '../connection.php';

$notif_count_query = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE username = ? AND is_read = 0");
$notif_count_query->bind_param("s", $username);
$notif_count_query->execute();
$notif_count_query->bind_result($notif_count);
$notif_count_query->fetch();
$notif_count_query->close();
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #7D3C98; position: fixed; top: 0; left: 0; width: 100%; z-index: 1000;">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center fw-bold" href="dashboard.php" style="color: #F4D03F;">
            <img src="../static/images/logo.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
            Small Shop
        </a>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>" href="dashboard.php">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'cart.php') ? 'active' : ''; ?>" href="cart.php">
                        Cart
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'orders.php') ? 'active' : ''; ?>" href="orders.php">
                        My Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'track_order.php') ? 'active' : ''; ?>" href="track_order.php">
                        Track Orders
                    </a>
                </li>
            </ul>
        </div>

        <div class="d-flex align-items-center">
            <button class="btn btn-outline-light position-relative me-3" style="border-color: #F4D03F; color: #F4D03F;" id="notifBtn">
    <i class="fas fa-bell"></i>
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="display: none;"></span>
</button>


            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" style="border-color: #F4D03F; color: #F4D03F;" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Profile
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation" style="border-color: #F4D03F;">
            <span class="navbar-toggler-icon"></span>
        </button>

    </div>
</nav>
<div style="margin-top: 80px;"></div>

<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="list-group" id="notifList">
                    <li class="list-group-item text-center">Loading...</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-link.active {
        background-color: #F4D03F !important;
        color: #333333 !important;
        border-radius: 5px;
        font-weight: bold;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
   $(document).ready(function () {
    async function fetchNotificationCount() {
    try {
        const response = await fetch("fetch_notifications_count.php");
        const count = await response.text(); // Assuming it returns just the count as a number
        console.log(count);
        if (count > 0) {
            $("#notifBtn .badge").text(count).show();
        } else {
            $("#notifBtn .badge").hide();
        }
    } catch (error) {
        console.error('Error fetching notification count:', error);
    }
}

$(document).ready(function () {
    // Initial fetch of notification count
    fetchNotificationCount();

    // Fetch notification count every 10 seconds (10000 ms)
    setInterval(fetchNotificationCount, 5000);
});


    // Open the modal and load notifications when the bell icon is clicked
    $("#notifBtn").click(function () {
        var myModal = new bootstrap.Modal(document.getElementById('notificationModal'), {
            keyboard: false
        });
        myModal.show(); // Show the modal

        $.ajax({
            url: "fetch_notifications.php", // Path to the PHP file for fetching the actual notifications
            type: "GET",
            dataType: "json",
            success: function (data) {
                let notifList = $("#notifList");
                notifList.empty();

                if (data.length === 0) {
                    notifList.append('<li class="list-group-item text-center">No new notifications</li>');
                } else {
                    data.forEach(notif => {
                        const formattedTime = timeAgo(notif.created_at);
                        notifList.append(`
                            <li class="list-group-item">
                                ${notif.message} <br>
                                <small class="text-muted">${formattedTime}</small>
                            </li>
                        `);
                    });
                }
            },
            error: function () {
                $("#notifList").html('<li class="list-group-item text-center text-danger">Error loading notifications</li>');
            }
        });
    });

    // Function to format the time ago text
    function timeAgo(date) {
        const now = new Date();
        const seconds = Math.floor((now - new Date(date)) / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);

        if (seconds < 60) {
            return `${seconds} seconds ago`;
        } else if (minutes < 60) {
            return `${minutes} minutes ago`;
        } else if (hours < 24) {
            return `${hours} hours ago`;
        } else {
            return `${days} days ago`;
        }
    }
});

</script>
