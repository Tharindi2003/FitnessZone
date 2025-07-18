<?php
session_start();

if (!isset($_SESSION['membershipid'])) {
    header("Location: login.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $package = $_POST['package'];
    $amount = $_POST['amount'];


    $_SESSION['package'] = $package;
    $_SESSION['amount'] = $amount;

    header("Location: card_details.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Package - FitZone Gym</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        /* Animated background elements */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 69, 0, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 69, 0, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, rgba(255, 69, 0, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
            animation: backgroundPulse 8s ease-in-out infinite alternate;
        }

        @keyframes backgroundPulse {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .container {
            width: 100%;
            max-width: 1200px;
            background: rgba(20, 20, 20, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 50px;
            position: relative;
            z-index: 1;
            box-shadow: 
                0 32px 64px rgba(0, 0, 0, 0.6),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            animation: containerFloat 6s ease-in-out infinite alternate;
        }

        @keyframes containerFloat {
            0% { transform: translateY(0px); }
            100% { transform: translateY(-5px); }
        }

        .container h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 3rem;
            font-weight: 900;
            background: linear-gradient(135deg, #ff4500 0%, #ff6b35 50%, #ff4500 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            background-size: 200% 200%;
            animation: gradientShift 4s ease-in-out infinite;
            position: relative;
            text-shadow: 0 0 40px rgba(255, 69, 0, 0.4);
        }

        @keyframes gradientShift {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        .container h2::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 4px;
            background: linear-gradient(90deg, transparent, #ff4500, transparent);
            border-radius: 2px;
            animation: lineGlow 3s ease-in-out infinite;
        }

        @keyframes lineGlow {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 69, 0, 0.3); }
            50% { box-shadow: 0 0 20px rgba(255, 69, 0, 0.6); }
        }

        .packages-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .package-form {
            position: relative;
            animation: fadeInUp 0.8s ease-out;
        }

        .package-form:nth-child(1) { animation-delay: 0.1s; }
        .package-form:nth-child(2) { animation-delay: 0.2s; }
        .package-form:nth-child(3) { animation-delay: 0.3s; }
        .package-form:nth-child(4) { animation-delay: 0.4s; }

        .package {
            background: linear-gradient(145deg, rgba(40, 40, 40, 0.9), rgba(30, 30, 30, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 35px;
            position: relative;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            backdrop-filter: blur(10px);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .package::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff4500, #ff6b35, #ff4500);
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .package::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 69, 0, 0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }

        .package:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: rgba(255, 69, 0, 0.4);
            box-shadow: 
                0 25px 50px rgba(255, 69, 0, 0.2),
                0 0 0 1px rgba(255, 69, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        .package:hover::before {
            opacity: 1;
        }

        .package:hover::after {
            opacity: 1;
        }

        .package h3 {
            font-size: 1.5rem;
            font-weight: 800;
            margin-bottom: 15px;
            color: #ffffff;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 2;
        }

        .package p {
            margin: 12px 0;
            color: rgba(255, 255, 255, 0.85);
            font-size: 1rem;
            line-height: 1.6;
            position: relative;
            z-index: 2;
        }

        .package .price {
            font-size: 2.2rem;
            font-weight: 900;
            color: #ff4500;
            margin: 25px 0;
            text-shadow: 0 0 15px rgba(255, 69, 0, 0.4);
            position: relative;
            z-index: 2;
        }

        .package .facilities {
            flex-grow: 1;
            margin-bottom: 25px;
        }

        .package .facilities::before {
            content: "✓ ";
            color: #ff4500;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 69, 0, 0.5);
        }

        button {
            background: linear-gradient(135deg, #ff4500 0%, #ff6b35 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 8px 20px rgba(255, 69, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            z-index: 2;
        }

        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.6s ease;
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 30px rgba(255, 69, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }

        button:hover::before {
            left: 100%;
        }

        button:active {
            transform: translateY(-1px);
        }

        a {
            text-decoration: none;
            color: white;
            font-weight: 700;
        }

        .details-section {
            text-align: center;
            margin-top: 50px;
            padding: 40px;
            background: rgba(255, 69, 0, 0.08);
            border: 1px solid rgba(255, 69, 0, 0.2);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
        }

        .details-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, transparent, rgba(255, 69, 0, 0.05), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .details-section:hover::before {
            opacity: 1;
        }

        .details-section h4 {
            margin-bottom: 25px;
            font-size: 1.3rem;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .details-section button {
            max-width: 350px;
            margin: 0 auto;
            background: linear-gradient(135deg, #ff4500 0%, #ff6b35 100%);
        }

        input[type="hidden"] {
            display: none;
        }

        /* Popular badge */
        .package.popular {
            position: relative;
            border-color: rgba(255, 69, 0, 0.6);
            background: linear-gradient(145deg, rgba(50, 35, 25, 0.9), rgba(40, 30, 20, 0.9));
        }

        .package.popular::before {
            opacity: 1;
        }

        .popular-badge {
            position: absolute;
            top: -1px;
            right: -1px;
            background: linear-gradient(135deg, #ff4500, #ff6b35);
            color: white;
            padding: 10px 20px;
            border-radius: 0 20px 0 20px;
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(255, 69, 0, 0.3);
            z-index: 3;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .package.popular .popular-badge {
            animation: pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 25px;
                margin: 10px;
            }
            
            .container h2 {
                font-size: 2.2rem;
            }
            
            .packages-grid {
                grid-template-columns: 1fr;
                gap: 25px;
            }
            
            .package {
                padding: 25px;
            }
            
            .package h3 {
                font-size: 1.3rem;
            }
            
            .package .price {
                font-size: 1.8rem;
            }
        }

        /* Glowing effect for ultimate package */
        .package.ultimate {
            background: linear-gradient(145deg, rgba(60, 40, 30, 0.9), rgba(50, 35, 25, 0.9));
            border-color: rgba(255, 69, 0, 0.4);
            box-shadow: 0 0 30px rgba(255, 69, 0, 0.1);
        }

        .package.ultimate::before {
            opacity: 0.7;
        }
    </style>
</head>

<body>
    
    <div class="container">
        <h2>Select Your Membership Package</h2>

        <div class="packages-grid">
            <form class="package-form" method="POST" action="">
                <div class="package">
                    <h3>Basic Package (1 Month)</h3>
                    <p class="facilities">Gym Access, Cardio, Locker Facility</p>
                    <p class="price">Rs. 1,000</p>
                    <input type="hidden" name="package" value="Basic (1 Month)">
                    <input type="hidden" name="amount" value="1000">
                    <button type="submit">Choose Basic Package</button>
                </div>
            </form>

            <form class="package-form" method="POST" action="">
                <div class="package">
                    <h3>Standard Package (3 Months)</h3>
                    <p class="facilities">Gym Access, Cardio, Locker Facility, Group Classes</p>
                    <p class="price">Rs. 2,500</p>
                    <input type="hidden" name="package" value="Standard (3 Months)">
                    <input type="hidden" name="amount" value="2500">
                    <button type="submit">Choose Standard Package</button>
                </div>
            </form>

            <form class="package-form" method="POST" action="">
                <div class="package popular">
                    <div class="popular-badge">Most Popular</div>
                    <h3>Premium Package (6 Months)</h3>
                    <p class="facilities">Gym Access, Cardio, Locker Facility, Group Classes, Personal Trainer</p>
                    <p class="price">Rs. 4,500</p>
                    <input type="hidden" name="package" value="Premium (6 Months)">
                    <input type="hidden" name="amount" value="4500">
                    <button type="submit">Choose Premium Package</button>
                </div>
            </form>

            <form class="package-form" method="POST" action="">
                <div class="package ultimate">
                    <h3>Ultimate Package (1 Year)</h3>
                    <p class="facilities">Gym Access, Cardio, Locker Facility, Group Classes, Personal Trainer, Free Diet Consultation</p>
                    <p class="price">Rs. 8,000</p>
                    <input type="hidden" name="package" value="Ultimate (1 Year)">
                    <input type="hidden" name="amount" value="8000">
                    <button type="submit">Choose Ultimate Package</button>
                </div>
            </form>
        </div>

        <div class="details-section">
            <h4>Already Paid? Access Your Details</h4>
            <button>
                <a href="details.php">Details Page</a>
            </button>
        </div>
    </div>
</body>

</html>