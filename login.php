<?php
// PHP: login.php
session_start();

// Redirect to index.php if already logged in
if (isset($_SESSION['username']) && !empty($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DIU Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #004d40, #00bfa5);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .login-container {
            width: 400px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .header {
            text-align: center;
            padding: 20px;
            background: #004d40;
            color: white;
        }

        .header img {
            width: 100px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 1.5rem;
            margin: 0;
        }

        .login-form {
            padding: 20px;
        }

        .login-form h2 {
            font-size: 1.2rem;
            color: #00695c;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 0.9rem;
            margin-bottom: 5px;
            color: #004d40;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .form-button {
            width: 100%;
            background: #004d40;
            color: white;
            font-size: 1rem;
            padding: 10px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .form-button:hover {
            background: #00796b;
        }

        .forgot-password {
            text-align: center;
            margin-top: 15px;
        }

        .forgot-password a {
            color: #004d40;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #00695c;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background: #004d40;
            color: white;
            font-size: 0.9rem;
        }

        .message {
            margin-top: 10px;
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="login-container">
    <!-- Header -->
    <div class="header">
        <img src="logo.png" alt="DIU Logo">
        <h1>Student Semester Result Portal</h1>
    </div>

    <!-- Login Form -->
    <div class="login-form">
        <h2>Login to Your Account</h2>
        <form id="login-form">
            <div class="form-group">
                <label for="username">Student ID *</label>
                <input type="text" id="username" placeholder="Enter your student ID" required>
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="form-button">Login</button>
        </form>
        <div id="message" class="message"></div>
        <div class="forgot-password">
            <a href="#">Forgot Password?</a>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        &copy; <?= date('Y') ?> All Rights Reserved @ Daffodil International University.
    </div>
</div>

<script>
    document.getElementById('login-form').addEventListener('submit', async function (event) {
        event.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value.trim();
        const messageElement = document.getElementById('message');

        messageElement.textContent = "Logging in...";
        messageElement.style.color = "#00695c";

        try {
            const response = await fetch('https://diursultv2-namk.onrender.com/dapis.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });

            if (!response.ok) {
                throw new Error(`HTTP Error: ${response.status}`);
            }

            const data = await response.json();

            if (data.loginResponse && data.loginResponse.message === 'success') {
                messageElement.textContent = "Login successful! Redirecting...";
                messageElement.style.color = "green";

                // Save session data
                await fetch('setsession.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        accessToken: data.loginResponse.accessToken,
                        username: data.loginResponse.name
                    })
                });

                // Redirect to index.php
                window.location.href = 'index.php';
            } else {
                throw new Error('Invalid username or password. Please try again.');
            }
        } catch (error) {
            messageElement.textContent = error.message;
            messageElement.style.color = "red";
        }
    });
</script>
</body>
</html>
