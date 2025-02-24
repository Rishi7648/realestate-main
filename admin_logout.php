<?php
session_start();

// Check if the admin confirmed the logout
if (isset($_POST['confirm_logout'])) {
    // Destroy the admin session
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session

    // Set a logout message (optional)
    $_SESSION['logout_message'] = "You have been logged out.";

    // Redirect to the admin login page
    header("Location: admin_login.php");
    exit();
} else {
    // If the admin did not confirm, redirect back to the admin panel
    header("Location: admin_dashboard.php");
    exit();
}
?>