<?php
session_start();

// Destroy the admin session
session_unset(); // Unsets all session variables
session_destroy(); // Destroys the session

// Set a session variable to display a logout message
$_SESSION['logout_message'] = "You have been logged out.";

// Redirect to the login page with a slight delay
header("Location: user_logout_redirect.php"); // Redirects to a new file that will show the message
exit();
?>
