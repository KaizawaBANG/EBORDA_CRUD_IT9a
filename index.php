<?php
session_start();

// Redirect logged-in users to the dashboard
if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>
