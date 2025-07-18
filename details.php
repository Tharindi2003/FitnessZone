<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fitnesszone');


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['membershipid'])) {
    echo "<script>alert('Unauthorized access. Please log in.');</script>";
    header("Location: login.php");
    exit();
}


$membership_id = $_SESSION['membershipid'];


$stmt = $conn->prepare("SELECT * FROM payment WHERE membership_id = ? ORDER BY payment_date DESC LIMIT 1");
$stmt->bind_param("s", $membership_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $payment = $result->fetch_assoc();
    $expiry_date = $payment['expiry_date'];
    $package = $payment['package'];
} else {

    $expiry_date = null;
    $package = null;
    $facilities = [];
}


$facilities = [];
switch ($package) {
    case "1 Month":
        $facilities = ["Access to Gym", "1 Free Personal Training Session", "Group Classes"];
        break;
    case "3 Months":
        $facilities = ["Access to Gym", "3 Free Personal Training Sessions", "Group Classes", "Spa Access"];
        break;
    case "6 Months":
        $facilities = ["Access to Gym", "6 Free Personal Training Sessions", "Group Classes", "Spa Access", "Nutrition Consultation"];
        break;
    case "1 Year":
        $facilities = ["Access to Gym", "Unlimited Personal Training Sessions", "Group Classes", "Spa Access", "Nutrition Consultation", "Exclusive Member Events"];
        break;
    default:
        $facilities = ["No facilities available for this package."];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Details - Elite Fitness</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            color: #ffffff;
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
        }

        /* Header Section */
        .header {
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
        }

        .header::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: -1;
        }

        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
            letter-spacing: -1px;
        }

        .header p {
            color: #999;
            font-size: 1.1rem;
        }

        /* Main Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35 0%, #f7931e 100%);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-icon {
            width: 24px;
            height: 24px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Package Details */
        .package-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 0.9rem;
            color: #999;
            font-weight: 500;
        }

        .info-value {
            font-size: 1rem;
            color: #ffffff;
            font-weight: 600;
        }

        .package-name {
            color: #ff6b35;
            font-weight: 700;
        }

        .expiry-date {
            color: #f7931e;
        }

        /* Facilities Section */
        .facilities-card {
            grid-column: 1 / -1;
        }

        .facilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .facility-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
            text-align: center;
        }

        .facility-item:hover {
            background: rgba(255, 107, 53, 0.1);
            border-color: rgba(255, 107, 53, 0.3);
            transform: translateY(-2px);
        }

        .facility-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .facility-name {
            font-size: 0.9rem;
            color: #ffffff;
            font-weight: 500;
        }

        /* Countdown Timer */
        .countdown-card {
            grid-column: 1 / -1;
            text-align: center;
            background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(247, 147, 30, 0.1) 100%);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .countdown-timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff6b35;
            margin-bottom: 1rem;
        }

        .countdown-display {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .countdown-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 1rem;
            border-radius: 12px;
            min-width: 80px;
            text-align: center;
        }

        .countdown-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            display: block;
        }

        .countdown-label {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Status Indicators */
        .status-active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-expired {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #999;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        .cta-button {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .header h1 {
                font-size: 2rem;
            }

            .card {
                padding: 1.5rem;
            }

            .countdown-display {
                gap: 0.5rem;
            }

            .countdown-item {
                min-width: 60px;
                padding: 0.75rem;
            }

            .facilities-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Membership Details</h1>
            <p>Your fitness journey overview</p>
        </div>

        <div class="content-grid">
            <!-- Package Information -->
            <div class="card">
                <h3>
                    <span class="card-icon">📋</span>
                    Package Information
                </h3>
                <div class="package-info">
                    <div class="info-row">
                        <span class="info-label">Current Package</span>
                        <span class="info-value package-name">
                            <?php echo $package ? htmlspecialchars($package) : "No active package"; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Expiry Date</span>
                        <span class="info-value expiry-date">
                            <?php echo $expiry_date ? date("F j, Y", strtotime($expiry_date)) : "Not available"; ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            <?php if ($expiry_date): ?>
                                <?php 
                                    $today = new DateTime();
                                    $expiry = new DateTime($expiry_date);
                                    $diff = $today->diff($expiry);
                                    
                                    if ($expiry < $today) {
                                        echo '<span class="status-expired">Expired</span>';
                                    } elseif ($diff->days <= 7) {
                                        echo '<span class="status-warning">Expires Soon</span>';
                                    } else {
                                        echo '<span class="status-active">Active</span>';
                                    }
                                ?>
                            <?php else: ?>
                                <span class="status-expired">No Package</span>
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Membership Stats -->
            <div class="card">
                <h3>
                    <span class="card-icon">📊</span>
                    Membership Stats
                </h3>
                <div class="package-info">
                    <div class="info-row">
                        <span class="info-label">Days Remaining</span>
                        <span class="info-value" id="days-remaining">
                            <?php 
                                if ($expiry_date) {
                                    $today = new DateTime();
                                    $expiry = new DateTime($expiry_date);
                                    $diff = $today->diff($expiry);
                                    echo $expiry > $today ? $diff->days . ' days' : 'Expired';
                                } else {
                                    echo 'N/A';
                                }
                            ?>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Member Since</span>
                        <span class="info-value">2024</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Renewal Required</span>
                        <span class="info-value">
                            <?php 
                                if ($expiry_date) {
                                    $today = new DateTime();
                                    $expiry = new DateTime($expiry_date);
                                    echo $expiry < $today ? 'Yes' : 'No';
                                } else {
                                    echo 'Yes';
                                }
                            ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Facilities Section -->
            <div class="card facilities-card">
                <h3>
                    <span class="card-icon">🏢</span>
                    Included Facilities
                </h3>
                <?php if (!empty($facilities)): ?>
                    <div class="facilities-grid">
                        <?php foreach ($facilities as $facility): ?>
                            <div class="facility-item">
                                <span class="facility-icon">
                                    <?php 
                                        $icons = [
                                            'gym' => '🏋️‍♂️',
                                            'pool' => '🏊‍♂️',
                                            'cardio' => '🏃‍♂️',
                                            'yoga' => '🧘‍♀️',
                                            'sauna' => '🧖‍♂️',
                                            'boxing' => '🥊',
                                            'cycling' => '🚴‍♂️',
                                            'crossfit' => '⚡',
                                            'personal training' => '👨‍🏫',
                                            'nutrition' => '🥗'
                                        ];
                                        
                                        $facility_lower = strtolower($facility);
                                        $icon = '🏢';
                                        
                                        foreach ($icons as $key => $emoji) {
                                            if (strpos($facility_lower, $key) !== false) {
                                                $icon = $emoji;
                                                break;
                                            }
                                        }
                                        
                                        echo $icon;
                                    ?>
                                </span>
                                <div class="facility-name"><?php echo htmlspecialchars($facility); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <h3>No Facilities Available</h3>
                        <p>Upgrade your membership to access premium facilities</p>
                        <a href="#" class="cta-button">Upgrade Now</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Countdown Timer -->
            <div class="card countdown-card">
                <h3>
                    <span class="card-icon">⏱️</span>
                    Membership Countdown
                </h3>
                <div class="countdown-timer" id="countdown-message">
                    Time remaining until expiry
                </div>
                <div class="countdown-display" id="countdown-display">
                    <div class="countdown-item">
                        <span class="countdown-number" id="days">0</span>
                        <span class="countdown-label">Days</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="hours">0</span>
                        <span class="countdown-label">Hours</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="minutes">0</span>
                        <span class="countdown-label">Minutes</span>
                    </div>
                    <div class="countdown-item">
                        <span class="countdown-number" id="seconds">0</span>
                        <span class="countdown-label">Seconds</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const expiryDate = "<?php echo $expiry_date; ?>";

        if (expiryDate) {
            const expiryDateTime = new Date(expiryDate).getTime();

            const countdown = setInterval(function() {
                const now = new Date().getTime();
                const timeLeft = expiryDateTime - now;

                const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                if (timeLeft > 0) {
                    document.getElementById("days").textContent = days;
                    document.getElementById("hours").textContent = hours;
                    document.getElementById("minutes").textContent = minutes;
                    document.getElementById("seconds").textContent = seconds;
                    
                    if (days <= 7) {
                        document.getElementById("countdown-message").textContent = "⚠️ Membership expires soon!";
                        document.getElementById("countdown-message").style.color = "#f59e0b";
                    }
                } else {
                    document.getElementById("countdown-message").textContent = "❌ Membership has expired";
                    document.getElementById("countdown-message").style.color = "#ef4444";
                    document.getElementById("countdown-display").style.opacity = "0.5";
                    clearInterval(countdown);
                }
            }, 1000);
        } else {
            document.getElementById("countdown-message").textContent = "No active membership found";
            document.getElementById("countdown-message").style.color = "#ef4444";
            document.getElementById("countdown-display").style.opacity = "0.5";
        }
    </script>
</body>

</html>