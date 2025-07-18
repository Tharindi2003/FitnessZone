<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fitnesszone');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['membershipid']) || !isset($_SESSION['package']) || !isset($_SESSION['amount'])) {
    header("Location: payment.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cardNumber = $_POST['card_number'];
    $cardHolder = $_POST['card_holder'];
    $expiry = $_POST['expiry'];
    $cvv = $_POST['cvv'];

    $membershipid = $_SESSION['membershipid'];
    $package = $_SESSION['package'];
    $amount = $_SESSION['amount'];

    $duration = 0;
    if (strpos($package, "1 Month") !== false) $duration = 1;
    elseif (strpos($package, "3 Months") !== false) $duration = 3;
    elseif (strpos($package, "6 Months") !== false) $duration = 6;
    elseif (strpos($package, "1 Year") !== false) $duration = 12;

    $expiryDate = date('Y-m-d', strtotime("+$duration months"));

    $sql = "INSERT INTO payment (membership_id, package, amount, expiry_date, card_number, card_holder_name) 
            VALUES ('$membershipid', '$package', '$amount', '$expiryDate', '$cardNumber', '$cardHolder')";
    if ($conn->query($sql) === TRUE) {

        header("Location: details.php");
        exit();
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details - FitZone Gym</title>
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

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 30% 70%, rgba(255, 69, 0, 0.1) 0%, transparent 50%),
                radial-gradient(circle at 70% 30%, rgba(255, 69, 0, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background: rgba(30, 30, 30, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            position: relative;
            z-index: 1;
            box-shadow: 
                0 25px 50px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.05);
            animation: fadeInUp 0.8s ease-out;
        }

        .heading {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ff4500 0%, #ff6b35 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(255, 69, 0, 0.3);
            position: relative;
        }

        .heading::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, transparent, #ff4500, transparent);
            border-radius: 2px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
        }

        .input-field {
            margin-bottom: 25px;
            position: relative;
            flex: 1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-field input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(40, 40, 40, 0.8);
            color: #ffffff;
            font-size: 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            backdrop-filter: blur(10px);
        }

        .input-field input:focus {
            outline: none;
            border-color: #ff4500;
            box-shadow: 
                0 0 0 3px rgba(255, 69, 0, 0.2),
                0 4px 20px rgba(255, 69, 0, 0.1);
            transform: translateY(-2px);
        }

        .input-field input::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-field input:valid {
            border-color: rgba(0, 255, 0, 0.3);
        }

        .submit-btn {
            width: 100%;
            padding: 18px 30px;
            background: linear-gradient(135deg, #ff4500 0%, #ff6b35 100%);
            color: white;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 
                0 4px 15px rgba(255, 69, 0, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            margin-top: 20px;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 8px 25px rgba(255, 69, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        /* Security badges */
        .security-info {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 69, 0, 0.05);
            border: 1px solid rgba(255, 69, 0, 0.2);
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }

        .security-badge {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .security-icon {
            width: 16px;
            height: 16px;
            fill: #ff4500;
        }

        /* Card preview */
        .card-preview {
            background: linear-gradient(135deg, #333 0%, #555 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .card-preview::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 50px;
            background: linear-gradient(45deg, #ff4500, #ff6b35);
            border-radius: 0 12px 0 50px;
            opacity: 0.3;
        }

        .card-number {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            letter-spacing: 2px;
            margin-bottom: 15px;
            color: rgba(255, 255, 255, 0.9);
        }

        .card-info {
            display: flex;
            justify-content: space-between;
            align-items: end;
        }

        .card-holder {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
        }

        .card-expiry {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .loading {
            animation: pulse 2s infinite;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
                margin: 10px;
            }
            
            .heading {
                font-size: 1.6rem;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .input-field input {
                padding: 12px 16px;
            }
        }

        /* Input field icons */
        .input-field.card-number::before {
            content: '💳';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.2rem;
        }

        .input-field.cvv::before {
            content: '🔒';
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="heading">Secure Payment</h2>
        
        <div class="card-preview">
            <div class="card-number" id="cardDisplay">**** **** **** ****</div>
            <div class="card-info">
                <div class="card-holder" id="holderDisplay">CARD HOLDER</div>
                <div class="card-expiry" id="expiryDisplay">MM/YY</div>
            </div>
        </div>

        <form method="POST" action="">
            <div class="input-field card-number">
                <label for="card_number" class="form-label">Card Number</label>
                <input type="text" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" 
                       maxlength="19" required>
            </div>
            
            <div class="input-field">
                <label for="card_holder" class="form-label">Card Holder Name</label>
                <input type="text" id="card_holder" name="card_holder" placeholder="John Doe" required>
            </div>
            
            <div class="form-row">
                <div class="input-field">
                    <label for="expiry" class="form-label">Expiry Date</label>
                    <input type="month" id="expiry" name="expiry" required>
                </div>
                <div class="input-field cvv">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="4" required>
                </div>
            </div>
            
            <button type="submit" class="submit-btn">Complete Payment</button>
        </form>

        <div class="security-info">
            <div class="security-badge">
                <svg class="security-icon" viewBox="0 0 24 24">
                    <path d="M12,1L3,5V11C3,16.55 6.84,21.74 12,23C17.16,21.74 21,16.55 21,11V5L12,1M12,7C13.4,7 14.8,8.6 14.8,10V11H16V18H8V11H9.2V10C9.2,8.6 10.6,7 12,7M12,8.2C11.2,8.2 10.4,8.7 10.4,10V11H13.6V10C13.6,8.7 12.8,8.2 12,8.2Z"/>
                </svg>
                SSL Encrypted
            </div>
            <div class="security-badge">
                <svg class="security-icon" viewBox="0 0 24 24">
                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M11,16.5L6.5,12L7.91,10.59L11,13.67L16.59,8.09L18,9.5L11,16.5Z"/>
                </svg>
                Secure Payment
            </div>
        </div>
    </div>

    <script>
        // Card number formatting
        document.getElementById('card_number').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            if (formattedValue.length <= 19) {
                e.target.value = formattedValue;
                document.getElementById('cardDisplay').textContent = formattedValue || '**** **** **** ****';
            }
        });

        // Card holder name
        document.getElementById('card_holder').addEventListener('input', function(e) {
            document.getElementById('holderDisplay').textContent = e.target.value.toUpperCase() || 'CARD HOLDER';
        });

        // Expiry date
        document.getElementById('expiry').addEventListener('input', function(e) {
            let date = new Date(e.target.value);
            let month = String(date.getMonth() + 1).padStart(2, '0');
            let year = String(date.getFullYear()).slice(-2);
            document.getElementById('expiryDisplay').textContent = e.target.value ? `${month}/${year}` : 'MM/YY';
        });

        // CVV input restriction
        document.getElementById('cvv').addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>

</html>