<?php

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'fitnesszone';

$conn = mysqli_connect($host, $username, $password, $dbname);


if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT c_firstname, c_lastname, c_age, c_email, c_gender, c_specialization FROM coach";
$result = mysqli_query($conn, $query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Coaches - Elite Fitness</title>
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
            line-height: 1.6;
        }

        /* Header Styles */
        header {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, 
                rgba(255, 107, 53, 0.1) 0%, 
                rgba(247, 147, 30, 0.1) 100%);
            z-index: -1;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        .back-btn {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .back-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        /* Main Content */
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 3rem 2rem;
        }

        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-title h2 {
            font-size: 2rem;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .section-title p {
            color: #999;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Coach Grid */
        .coach-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .coach-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .coach-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff6b35 0%, #f7931e 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .coach-card:hover::before {
            transform: scaleX(1);
        }

        .coach-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 107, 53, 0.3);
        }

        .coach-header {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .coach-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            margin-right: 1rem;
            flex-shrink: 0;
        }

        .coach-name {
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.25rem;
        }

        .coach-specialization {
            color: #ff6b35;
            font-size: 0.9rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .coach-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .coach-detail {
            display: flex;
            flex-direction: column;
        }

        .coach-detail-label {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }

        .coach-detail-value {
            font-size: 1rem;
            color: #ffffff;
            font-weight: 500;
        }

        .coach-email {
            grid-column: 1 / -1;
            margin-top: 0.5rem;
        }

        .coach-email .coach-detail-value {
            color: #ff6b35;
            word-break: break-all;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #999;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
            color: #ffffff;
        }

        .empty-state p {
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            header h1 {
                font-size: 2rem;
            }

            main {
                padding: 2rem 1rem;
            }

            .coach-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .coach-card {
                padding: 1.5rem;
            }

            .coach-header {
                flex-direction: column;
                text-align: center;
            }

            .coach-avatar {
                margin: 0 0 1rem 0;
            }

            .coach-details {
                grid-template-columns: 1fr;
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #ff6b35;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Specialization badges */
        .specialization-badge {
            background: rgba(255, 107, 53, 0.2);
            color: #ff6b35;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 107, 53, 0.3);
            display: inline-block;
            margin-top: 0.5rem;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <h1>Meet Our Coaches</h1>
            <a href="index.html" class="back-btn">← Back to Home</a>
        </div>
    </header>

    <main>
        <div class="section-title">
            <h2>Professional Training Staff</h2>
            <p>Our certified coaches are dedicated to helping you achieve your fitness goals with personalized training programs and expert guidance.</p>
        </div>

        <div class="coach-grid">
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <div class="coach-card">
                        <div class="coach-header">
                            <div class="coach-avatar">
                                <?php 
                                    $initials = strtoupper(substr($row['c_firstname'], 0, 1) . substr($row['c_lastname'], 0, 1));
                                    echo $initials;
                                ?>
                            </div>
                            <div>
                                <div class="coach-name">
                                    <?php echo htmlspecialchars($row['c_firstname'] . " " . $row['c_lastname']); ?>
                                </div>
                                <div class="coach-specialization">
                                    <?php echo htmlspecialchars(ucfirst($row['c_specialization'])); ?>
                                </div>
                            </div>
                        </div>

                        <div class="coach-details">
                            <div class="coach-detail">
                                <span class="coach-detail-label">Age</span>
                                <span class="coach-detail-value"><?php echo htmlspecialchars($row['c_age']); ?> years</span>
                            </div>
                            <div class="coach-detail">
                                <span class="coach-detail-label">Gender</span>
                                <span class="coach-detail-value"><?php echo htmlspecialchars(ucfirst($row['c_gender'])); ?></span>
                            </div>
                            <div class="coach-detail coach-email">
                                <span class="coach-detail-label">Contact Email</span>
                                <span class="coach-detail-value"><?php echo htmlspecialchars($row['c_email']); ?></span>
                            </div>
                        </div>

                        <div class="specialization-badge">
                            Certified <?php echo htmlspecialchars(ucfirst($row['c_specialization'])); ?> Trainer
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="empty-state">
                    <h3>No Coaches Available</h3>
                    <p>We're currently updating our coaching staff. Please check back soon to meet our professional trainers.</p>
                </div>
            <?php endif; ?>
        </div>

        <?php
        if (isset($result)) {
            mysqli_free_result($result);
        }
        if (isset($conn)) {
            mysqli_close($conn);
        }
        ?>
    </main>
</body>

</html>