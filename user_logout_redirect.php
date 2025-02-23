<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <script>
        // Show the logout message using JavaScript after the page is loaded
        window.onload = function() {
            var message = "<?php echo isset($_SESSION['logout_message']) ? $_SESSION['logout_message'] : ''; ?>";
            if (message) {
                alert(message);  // Show the message in an alert box
                <?php unset($_SESSION['logout_message']); ?> // Unset the session message after it's shown
            }
            // Redirect to login page after 2 seconds
            setTimeout(function() {
                window.location.href = 'login.php';  // Redirect to the login page
            }, 2000);
        };
    </script>
</head>
<body>
    <!-- Optional: You can add a message or anything here to show on the page before the redirect. -->
    <h1>You are being logged out...</h1>
</body>
</html>
