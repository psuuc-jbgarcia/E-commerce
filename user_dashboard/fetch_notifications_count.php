<?php
session_start();
require '../connection.php';

$username = $_SESSION['email'];

$notif_count_query = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE username = ? AND is_read = 0");
$notif_count_query->bind_param("s", $username);
$notif_count_query->execute();
$notif_count_query->bind_result($notif_count);
$notif_count_query->fetch();
$notif_count_query->close();

echo $notif_count;
?>
