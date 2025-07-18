<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'fitnesszone');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM Staff WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $storedPassword);
        $stmt->fetch();

        if ($password === $storedPassword) {
            $_SESSION['staff_id'] = $id;
            header("Location: staff_dashboard.php");
            exit;
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Invalid username or password.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Portal - Gym Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 420px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 8px;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.1),
                0 4px 8px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            margin: 20px;
        }

        .header-section {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            padding: 40px 40px 30px;
            text-align: center;
            position: relative;
        }

        .header-section::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .logo-container {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .logo-container svg {
            width: 32px;
            height: 32px;
            fill: white;
        }

        .header-title {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .header-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 400;
        }

        .form-section {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #2c3e50;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
            letter-spacing: 0.25px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 6px;
            font-size: 16px;
            color: #2c3e50;
            background: white;
            transition: all 0.2s ease;
            outline: none;
        }

        .form-input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .form-input::placeholder {
            color: #95a5a6;
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            padding: 4px;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #2c3e50;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 6px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 8px;
        }

        .login-button:hover {
            background: linear-gradient(135deg, #2980b9, #1f5582);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(52, 152, 219, 0.3);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .login-button:disabled {
            background: #bdc3c7;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            background: #fff5f5;
            border: 1px solid #feb2b2;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 6px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-icon {
            width: 16px;
            height: 16px;
            fill: #c53030;
        }

        .footer-links {
            text-align: center;
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e1e8ed;
        }

        .footer-links a {
            color: #7f8c8d;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s ease;
        }

        .footer-links a:hover {
            color: #3498db;
        }

        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .button-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .security-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 6px 12px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 12px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .security-icon {
            width: 14px;
            height: 14px;
            fill: rgba(255, 255, 255, 0.9);
        }

        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
            }
            
            .header-section {
                padding: 30px 30px 20px;
            }
            
            .form-section {
                padding: 30px;
            }
            
            .header-title {
                font-size: 20px;
            }
        }

        .login-container {
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
</head>

<body>
    <div class="security-badge">
        <svg class="security-icon" viewBox="0 0 24 24">
            <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V16H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
        </svg>
        Secure Login
    </div>

    <div class="login-container">
        <div class="header-section">
            <div class="logo-container">
                <svg viewBox="0 0 24 24">
                    <path d="M20.57 14.86L22 13.43L20.57 12L17 15.57L8.43 7L12 3.43L10.57 2L9.14 3.43L7.71 2L5.57 4.14L4.14 2.71L2.71 4.14L4.14 5.57L2 7.71L3.43 9.14L4.86 7.71L13.43 16.29L9.86 19.86L11.29 21.29L12.71 19.86L14.14 21.29L16.29 19.14L17.71 20.57L19.14 19.14L17.71 17.71L19.86 15.57L21.29 14.14L19.86 12.71L20.57 14.86Z"/>
                </svg>
            </div>
            <h1 class="header-title">Staff Portal</h1>
            <p class="header-subtitle">Secure Access to Management System</p>
        </div>

        <div class="form-section">
            <div class="error-message" style="display: none;">
                <svg class="error-icon" viewBox="0 0 24 24">
                    <path d="M12,2C17.53,2 22,6.47 22,12C22,17.53 17.53,22 12,22C6.47,22 2,17.53 2,12C2,6.47 6.47,2 12,2M15.59,7L12,10.59L8.41,7L7,8.41L10.59,12L7,15.59L8.41,17L12,13.41L15.59,17L17,15.59L13.41,12L17,8.41L15.59,7Z"/>
                </svg>
                Invalid credentials. Please verify your username and password.
            </div>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" required autocomplete="username" placeholder="Enter your username">
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="password-container">
                        <input type="password" id="password" name="password" class="form-input" required autocomplete="current-password" placeholder="Enter your password">
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <span id="passwordToggleIcon">👁</span>
                        </button>
                    </div>
                </div>

                <input type="hidden" name="action" value="login">
                
                <button type="submit" class="login-button" id="loginButton">
                    <div class="button-content">
                        <span class="button-text">Sign In</span>
                        <div class="loading-spinner" id="loadingSpinner"></div>
                    </div>
                </button>
            </form>

            <div class="footer-links">
                <a href="#" onclick="showForgotPassword()">Forgot Password?</a>
                <span style="margin: 0 8px; color: #bdc3c7;">|</span>
                <a href="#" onclick="showSupport()">IT Support</a>
            </div>
        </div>
    </div>

    <script>
        // Password 
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁';
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const button = document.getElementById('loginButton');
            const buttonText = button.querySelector('.button-text');
            const spinner = document.getElementById('loadingSpinner');
            
            button.disabled = true;
            buttonText.textContent = 'Signing In...';
            spinner.style.display = 'block';
            
            setTimeout(() => {
                button.disabled = false;
                buttonText.textContent = 'Sign In';
                spinner.style.display = 'none';
            }, 3000);
        });

        function showForgotPassword() {
            alert('Please contact your system administrator to reset your password.\n\nEmail: admin@gym.com\nPhone: (555) 123-4567');
        }

        function showSupport() {
            alert('IT Support Contact:\n\nEmail: support@gym.com\nPhone: (555) 123-4567\nExt: 101\n\nOffice Hours: Mon-Fri 8:00 AM - 6:00 PM');
        }

        window.addEventListener('beforeunload', function() {
            document.getElementById('username').value = '';
            document.getElementById('password').value = '';
        });

        document.getElementById('username').focus();
    </script>
</body>

</html>