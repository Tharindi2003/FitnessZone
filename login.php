<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fitnesszone');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $membershipId = $_POST['membershipid'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE member_id = ?");
    $stmt->bind_param("s", $membershipId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password == $user['password']) {
            session_regenerate_id();
            $_SESSION['membershipid'] = $membershipId;
            $_SESSION['name'] = $user['name'];
            header("Location: payment.php");
            exit();
        } else {
            echo "<script>alert('Incorrect password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Membership ID not found. Please check your details.');</script>";
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
    <title>Gym Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            background-image: url(https://images.unsplash.com/photo-1507398941214-572c25f4b1dc?q=80&w=1973&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        .back-button {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 3;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 69, 0, 0.3);
            border-radius: 50px;
            padding: 12px 20px;
            color: #ff4500;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .back-button:hover {
            background: rgba(255, 69, 0, 0.1);
            border-color: #ff4500;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 69, 0, 0.3);
        }

        .back-button::before {
            content: '←';
            font-size: 16px;
            font-weight: bold;
        }

        .login-wrapper {
            position: relative;
            z-index: 2;
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-container {
            width: 420px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gym-logo {
            margin-bottom: 30px;
        }

        .gym-logo h1 {
            color: #ff4500;
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .gym-logo p {
            color: #666;
            font-size: 0.9em;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .form-title {
            color: #333;
            font-size: 1.8em;
            margin-bottom: 30px;
            font-weight: 600;
        }

        .input-group {
            position: relative;
            margin-bottom: 25px;
        }

        .input-group input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.9);
        }

        .input-group input:focus {
            outline: none;
            border-color: #ff4500;
            box-shadow: 0 0 10px rgba(255, 69, 0, 0.2);
            transform: translateY(-2px);
        }

        .input-group input::placeholder {
            color: #999;
            transition: all 0.3s ease;
        }

        .input-group input:focus::placeholder {
            color: transparent;
        }

        .login-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #ff4500, #ff6a00);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: linear-gradient(135deg, #ff6a00, #ffa500);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 69, 0, 0.4);
        }

        .login-btn:active {
            transform: translateY(-1px);
        }

        .additional-links {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
        }

        .additional-links a {
            color: #ff4500;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .additional-links a:hover {
            color: #ff6a00;
            text-decoration: underline;
        }

        .strength-indicator {
            height: 3px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 5px;
            overflow: hidden;
        }

        .strength-bar {
            height: 100%;
            background: linear-gradient(90deg, #ff4500, #ffa500);
            width: 0%;
            transition: width 0.3s ease;
        }

        @media (max-width: 480px) {
            .form-container {
                width: 90%;
                padding: 30px 20px;
                margin: 20px;
            }
            
            .gym-logo h1 {
                font-size: 2em;
            }

            .back-button {
                top: 20px;
                left: 20px;
                padding: 10px 16px;
                font-size: 13px;
            }
        }

        /* Loading animation */
        .loading {
            position: relative;
            overflow: hidden;
        }

        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>

<body>
    <a href="index.html" class="back-button">Back</a>
    
    <div class="login-wrapper">
        <form class="form-container" method="POST" action="">
            <div class="gym-logo">
                <h1>FitZone</h1>
                <p>Strength & Fitness</p>
            </div>
            
            <h2 class="form-title">Member Login</h2>
            
            <div class="input-group">
                <input type="text" name="membershipid" placeholder="Membership ID" required>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required id="password">
                <div class="strength-indicator">
                    <div class="strength-bar" id="strengthBar"></div>
                </div>
            </div>
            
            <button type="submit" class="login-btn">Login</button>
            
            <div class="additional-links">
                <a href="#">Forgot Password?</a> | 
                <a href="register.php">New Member Registration</a>
            </div>
        </form>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');

        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strength = Math.min(password.length * 10, 100);
            strengthBar.style.width = strength + '%';
        });

        document.querySelector('form').addEventListener('submit', function(e) {
            const button = document.querySelector('.login-btn');
            button.textContent = 'Logging in...';
            button.classList.add('loading');
        });

        const inputs = document.querySelectorAll('input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>