<?php
session_start();

// Check if the user confirmed the logout
if (isset($_POST['confirm_logout'])) {
    // Destroy the session
    session_unset(); // Unsets all session variables
    session_destroy(); // Destroys the session

    // Set a session variable to display a logout message
    $_SESSION['logout_message'] = "You have been logged out.";

    // Redirect to the login page
    header("Location: login.php");
    exit();
} else {
    // If the user did not confirm, redirect them back to the previous page
    header("Location: previous_page.php");
    exit();
}
?>