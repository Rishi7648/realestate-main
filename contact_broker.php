<?php
// Get the referring URL (the page the user came from)
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'buy.php'; // Fallback to 'buy.php' if referer is not set
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Broker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #2ecc71;
        }
        p {
            font-size: 1.2em;
        }
        button {
            padding: 10px 20px;
            background-color: #2ecc71;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            margin-top: 20px;
        }
        button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <h1>Contact the Broker</h1>
    <p> Contact Number: <strong>9823167724(Raja Prasad)</strong></p>
    <p> Contact Number: <strong>9823169968(Dipak Praja)</strong></p>
    <p>Please call the broker to  purchase property.</p>

    <!-- Button to go back -->
    <button onclick="goBack()">Go Back</button>

    <script>
        // JavaScript function to go back to the referring page
        function goBack() {
            window.location.href = "<?= $referer ?>"; // Redirect to the referring URL
        }
    </script>
</body>
</html>