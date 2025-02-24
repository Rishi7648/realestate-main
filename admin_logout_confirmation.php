<?php
session_start();

// Check if the user is an admin (optional, for security)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect to admin login if not logged in
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Logout Confirmation</title>
    <style>
        /* Button styles */
        .button {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin: 5px;
        }
        .button.yes {
            background-color: #dc3545; /* Red for logout */
            color: white;
        }
        .button.yes:hover {
            background-color: #c82333; /* Darker red on hover */
        }
        .button.cancel {
            background-color: #6c757d; /* Gray for cancel */
            color: white;
        }
        .button.cancel:hover {
            background-color: #5a6268; /* Darker gray on hover */
        }
    </style>
</head>
<body>
    <h1>Are you sure you want to log out?</h1>
    <form action="admin_logout.php" method="POST">
        <button type="submit" name="confirm_logout" class="button yes">Yes, Logout</button>
    </form>
    <form action="admin_cancel_logout.php" method="POST">
        <button type="submit" name="cancel_logout" class="button cancel">Cancel</button>
    </form>
</body>
</html>