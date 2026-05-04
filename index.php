<?php
session_start();

if (isset($_SESSION['username'])) {
    header("Location: pages/dashboard.html");
    exit();
}

header("Location: pages/login.html");
exit();
?>
