<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <style>
        /* Include the CSS styles here */
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
            background-color: #dc3545;
            color: white;
        }
        .button.yes:hover {
            background-color: #c82333;
        }
        .button.cancel {
            background-color: #6c757d;
            color: white;
        }
        .button.cancel:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <h1>Are you sure you want to log out?</h1>
    <form action="logout.php" method="POST">
        <button type="submit" name="confirm_logout" class="button yes">Yes, Logout</button>
    </form>
    <form action="cancel_logout.php" method="POST">
        <button type="submit" name="cancel_logout" class="button cancel">Cancel</button>
    </form>
</body>
</html>