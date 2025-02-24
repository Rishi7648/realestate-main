<?php
include 'db.php';
/* form is submitted using post method*/ 
/*retriving form data */ 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = trim($_POST['firstname']);
    $lastName = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    // Validate inputs
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Invalid email format!</p>";
        exit();
    }

    if (!preg_match('/^\d{10}$/', $phone)) {
        echo "<p>Invalid phone number! Must be 10 digits.</p>";
        exit();
    }

    // Password strength validation
    if (!preg_match('/^(?=.*\d)(?=.*[A-Za-z])[A-Za-z\d]{8,}$/', $password)) {
        echo "<p>Password is weak! Please use at least 6 characters with a mix of letters and numbers.</p>";
        exit();
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Check if the email already exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        if ($stmt->rowCount() > 0) {
            echo "<p>Email already exists!</p>";
        } else {
            // Insert user into the database
            $sql = "INSERT INTO users (first_name, last_name, email, phone, password) 
                    VALUES (:first_name, :last_name, :email, :phone, :password)";
            $stmt = $conn->prepare($sql);
/* Binding Data and Executing the Query*/

            $data = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'password' => $hashedPassword
            ];

            if ($stmt->execute($data)) {
                echo "<p>Signup successful!</p>";
                header("Location: login.php");
                exit();
            } else {
                echo "<p>Error during signup. Please try again.</p>";
            }
        }
    } catch (PDOException $e) {
        echo "<p>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <style>
        /* General body styles */
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            background: linear-gradient(135deg, #ff7e5f, #feb47b); /*background color behind of signup form*/
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        /* Form container styling */
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 30px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            animation: fadeIn 1s ease-in-out;
        }

        /* Header styles */
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 28px;
            font-weight: 600;
        }

        /* Input fields */
        .form-container input {
            width: 100%;
            padding: 14px 16px;
            margin: 10px 0;
            border: none;
            border-radius: 25px;
            background: rgba(255, 255, 255, 0.8);
            color: #333;
            font-size: 14px;
            box-shadow: inset 0 3px 5px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        /* Input field focus */
        .form-container input:focus {
            outline: none;
            background: rgba(255, 255, 255, 1);
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.7);
        }

        /* Password container for eye icon */
        .password-container {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #007bff;
            font-size: 20px;
        }

        /* Submit button */
        .form-container button {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 25px;
            background: #6a11cb;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.4s ease;
        }

        .form-container button:hover {
            background: linear-gradient(45deg, #2575fc, #6a11cb);
        }

        /* Forgot Password link */
        .form-container .forgot-password {
            text-align: center;
            margin-top: 10px;
        }

        /* Signup link */
        .form-container p {
            text-align: center;
            margin-top: 15px;
        }

        .form-container p a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .form-container p a:hover {
            text-decoration: underline;
        }

        /* Keyframes for fade-in animation */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Responsive design */
        @media (max-width: 480px) {
            .form-container {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Create Your Account</h2>
        <form method="POST">
            <input type="text" name="firstname" placeholder="First Name" required>
            <input type="text" name="lastname" placeholder="Last Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="phone" placeholder="Phone Number" required pattern="\d{10}">
            
            <div class="password-container">
                <input type="password" id="signup-password" placeholder="Password" name="password" required>
                <span class="eye-icon" onclick="togglePasswordVisibility()">&#128065;</span>
            </div>
            
            <button type="submit">Signup</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
        <div class="forgot-password">
            <a href="forgot-password.php">Forgot Password?</a>
        </div>
    </div>

    <script>
        let passwordVisible = false;

        function togglePasswordVisibility() {
            const passwordField = document.getElementById('signup-password');
            const eyeIcon = document.querySelector('.eye-icon');
            if (passwordVisible) {
                passwordField.type = "password";
                eyeIcon.innerHTML = "&#128065;";
                passwordVisible = false;
            } else {
                passwordField.type = "text";
                eyeIcon.innerHTML = "&#128586;";
                passwordVisible = true;
            }
        }
    </script>
</body>
</html>
