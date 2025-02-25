<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user from the database
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists and verify the password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, start the session
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on user role
        header("Location: " . ($user['role'] == 'admin' ? 'admin_dashboard.php' : 'index.php'));
        exit();
    } else {
        // Invalid credentials
        echo "<script>alert('Invalid email or password!')</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        /* Global Styles */
        body {
            font-family: 'Poppins', sans-serif;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        /* Container */
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        /* Heading */
        .form-container h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #333;
        }

        /* Input Fields */
        .form-container input {
            width: 100%;
            padding: 15px;
            margin: 15px 0;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-container input:focus {
            border-color: #ff7e5f;
            box-shadow: 0 0 8px rgba(255, 126, 95, 0.4);
        }

        /* Password Container */
        .password-container {
            position: relative;
        }

        .eye-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            cursor: pointer;
            color: #999;
        }

        /* Button */
        .form-container button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff7e5f, #feb47b);
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .form-container button:hover {
            background: linear-gradient(135deg, #feb47b, #ff7e5f);
            transform: translateY(-3px);
            box-shadow: 0 8px 15px rgba(255, 126, 95, 0.3);
        }

        /* Links */
        .form-container p {
            font-size: 14px;
            color: #666;
            margin-top: 15px;
        }

        .form-container p a {
            color: #ff7e5f;
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .form-container p a:hover {
            color: #feb47b;
        }

        /* Forgot Password Button */
        .forgot-password {
            font-size: 14px;
            color: blue;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            transition: color 0.3s ease;
        }

        .forgot-password:hover {
            color: #feb47b;
        }

        /* Animation */
        .form-container {
            animation: slide-in 0.8s ease-out;
        }

        @keyframes slide-in {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Welcome Back</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            
            <div class="password-container">
                <input type="password" id="login-password" placeholder="Enter Password" name="password" required>
                <span class="eye-icon" onclick="togglePassword('login-password')">&#128065;</span>
            </div>

            <button type="submit">Login</button>
            
            <p>Don't have an account? <a href="signup.php">Sign up</a></p>
            <a href="#" class="forgot-password" onclick="showForgotPasswordMessage()">Forgot Password?</a>
        </form>
    </div>

    <script>
        let passwordVisible = false;

        function togglePassword(id) {
            const passwordField = document.getElementById(id);
            const eyeIcon = document.querySelector('.eye-icon');

            if (passwordVisible) {
                passwordField.type = "password";
                eyeIcon.innerHTML = "&#128065;";
                passwordVisible = false;
            } else {
                passwordField.type = "text";
                eyeIcon.innerHTML = "&#128586;";
                passwordVisible = true;

                setTimeout(() => {
                    passwordField.type = "password";
                    eyeIcon.innerHTML = "&#128065;";
                    passwordVisible = false;
                }, 5000);
            }
        }

        function showForgotPasswordMessage() {
            alert("This function isn't working right now. Please try again later.");
        }
    </script>
</body>
</html>