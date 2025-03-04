<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            background-color: #f8f9fa; /* Light background color */
        }

        .logout-container {
            text-align: center;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        }

        h1 {
            margin-bottom: 20px;
            color: #333;
        }

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
    <div class="logout-container">
        <h1>Are you sure you want to log out?</h1>
        <!-- POST request is an HTTP method used to send data from the client (browser) to the server.
         commonly used in forms when submitting user input -->
        <form action="logout.php" method="POST">
            <button type="submit" name="confirm_logout" class="button yes">Yes, Logout</button>
        </form>
        <form action="cancel_logout.php" method="POST">
            <button type="submit" name="cancel_logout" class="button cancel">Cancel</button>
        </form>
    </div>
</body>
</html>