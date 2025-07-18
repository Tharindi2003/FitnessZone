<?php

session_start();

if (!isset($_SESSION['staff_name'])) {
    $_SESSION['staff_name'] = 'John Doe'; // Example
    $_SESSION['staff_email'] = 'john.doe@example.com';
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "fitnesszone";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_staff = "SELECT username, email FROM staff WHERE id = 1";
$result_staff = $conn->query($sql_staff);

if ($result_staff && $result_staff->num_rows > 0) {
    $staff = $result_staff->fetch_assoc();
    $_SESSION['staff_name'] = $staff['username'];
    $_SESSION['staff_email'] = $staff['email'];
} else {
    $_SESSION['staff_name'] = "Unknown Staff";
    $_SESSION['staff_email'] = "unknown@example.com";
}

$sql_total = "SELECT COUNT(*) AS total FROM Appointment";
$result_total = $conn->query($sql_total);
$total_appointments = $result_total->fetch_assoc()['total'];

$sql_today = "SELECT COUNT(*) AS total FROM Appointment WHERE date = CURDATE()";
$result_today = $conn->query($sql_today);
$today_appointments = $result_today->fetch_assoc()['total'];

$sql_week = "SELECT COUNT(*) AS total FROM Appointment WHERE YEARWEEK(date, 1) = YEARWEEK(CURDATE(), 1)";
$result_week = $conn->query($sql_week);
$week_appointments = $result_week->fetch_assoc()['total'];

$sql_upcoming = "SELECT id, name, date, time FROM Appointment WHERE date >= CURDATE() ORDER BY date, time LIMIT 5";
$result_upcoming = $conn->query($sql_upcoming);

$upcoming_appointments = [];
if ($result_upcoming->num_rows > 0) {
    while ($row = $result_upcoming->fetch_assoc()) {
        $upcoming_appointments[] = $row;
    }
}

$appointments_data = [];
$fees_data = [];
$registrations_data = [];
$days = [];

for ($i = 1; $i <= date('t'); $i++) {
    $day = date('Y-m') . '-' . sprintf('%02d', $i);
    $days[] = $i;

    $sql_day = "SELECT COUNT(*) AS total FROM Appointment WHERE date = '$day'";
    $result_day = $conn->query($sql_day);
    $appointments_data[] = $result_day->fetch_assoc()['total'];

    $sql_fees = "SELECT SUM(amount) AS total FROM payment WHERE payment_date LIKE '$day%'";
    $result_fees = $conn->query($sql_fees);
    $fees_data[] = $result_fees->fetch_assoc()['total'] ?? 0; // Default to 0 if no records

    $registrations_data[] = $appointments_data[count($appointments_data) - 1];
}

$conn->close();
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Gym Management System</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            color: #2c3e50;
        }

        /* Professional grid background */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            z-index: -1;
            pointer-events: none;
        }

        .header {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            padding: 20px 40px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
            backdrop-filter: blur(10px);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-container {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(52, 152, 219, 0.3);
        }

        .logo-container svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }

        .user-info {
            text-align: right;
            font-size: 14px;
            line-height: 1.4;
        }

        .user-info strong {
            color: #3498db;
            font-weight: 600;
        }

        .user-info p {
            margin: 2px 0;
            color: rgba(255, 255, 255, 0.9);
        }

        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px;
        }

        .greeting {
            background: rgba(255, 255, 255, 0.98);
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border-left: 4px solid #3498db;
        }

        .greeting p {
            font-size: 18px;
            font-weight: 500;
            color: #2c3e50;
        }

        .metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 24px;
            margin-bottom: 40px;
        }

        .metric-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            padding: 32px 24px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .metric-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .metric-card:nth-child(2)::before {
            background: linear-gradient(135deg, #27ae60, #229954);
        }

        .metric-card:nth-child(3)::before {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .metric-card h2 {
            font-size: 48px;
            font-weight: 700;
            margin: 16px 0;
            color: #2c3e50;
            letter-spacing: -1px;
        }

        .metric-card p {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .section-header {
            background: rgba(255, 255, 255, 0.98);
            padding: 20px 24px;
            border-radius: 12px 12px 0 0;
            border-bottom: 1px solid #e1e8ed;
            margin-bottom: 0;
        }

        .section-header h2 {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
            letter-spacing: -0.3px;
        }

        .appointments-container {
            margin-bottom: 40px;
        }

        .appointments-table {
            width: 100%;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 0 0 12px 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .appointments-table th,
        .appointments-table td {
            padding: 16px 20px;
            text-align: left;
            border-bottom: 1px solid #e1e8ed;
        }

        .appointments-table th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .appointments-table td {
            color: #2c3e50;
            font-size: 14px;
        }

        .appointments-table tr:hover {
            background: #f8f9fa;
        }

        .appointments-table tr:last-child td {
            border-bottom: none;
        }

        .no-appointments {
            text-align: center;
            color: #7f8c8d;
            font-style: italic;
            padding: 40px;
        }

        .charts-section {
            margin-top: 40px;
        }

        .chart-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .chart-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .chart-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: -0.2px;
        }

        .chart-wrapper {
            position: relative;
            height: 300px;
            margin-bottom: 12px;
        }

        .chart-description {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            font-weight: 500;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .main-container {
                padding: 20px;
            }

            .metrics {
                grid-template-columns: 1fr;
            }

            .chart-container {
                grid-template-columns: 1fr;
            }

            .appointments-table {
                font-size: 12px;
            }

            .appointments-table th,
            .appointments-table td {
                padding: 12px 8px;
            }
        }

        /* Loading animation */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #e1e8ed;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Status indicators */
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-active {
            background: #27ae60;
        }

        .status-pending {
            background: #f39c12;
        }

        .status-cancelled {
            background: #e74c3c;
        }
    </style>
</head>

<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
    </div>

    <div class="header">
        <div class="header-content">
            <div class="header-left">
                <div class="logo-container">
                    <svg viewBox="0 0 24 24">
                        <path d="M20.57 14.86L22 13.43L20.57 12L17 15.57L8.43 7L12 3.43L10.57 2L9.14 3.43L7.71 2L5.57 4.14L4.14 2.71L2.71 4.14L4.14 5.57L2 7.71L3.43 9.14L4.86 7.71L13.43 16.29L9.86 19.86L11.29 21.29L12.71 19.86L14.14 21.29L16.29 19.14L17.71 20.57L19.14 19.14L17.71 17.71L19.86 15.57L21.29 14.14L19.86 12.71L20.57 14.86Z"/>
                    </svg>
                </div>
                <h1>Staff Dashboard</h1>
            </div>
            <div class="user-info">
                <p>Welcome, <strong>John Smith</strong>!</p>
                <p>Email: john.smith@gym.com</p>
            </div>
        </div>
    </div>

    <div class="main-container">
        <div class="greeting fade-in">
            <p>Hello John Smith, here are the latest updates:</p>
        </div>

        <div class="metrics fade-in">
            <div class="metric-card">
                <h2>12</h2>
                <p>Appointments Today</p>
            </div>
            <div class="metric-card">
                <h2>48</h2>
                <p>Appointments This Week</p>
            </div>
            <div class="metric-card">
                <h2>156</h2>
                <p>Total Appointments</p>
            </div>
        </div>

        <div class="appointments-container fade-in">
            <div class="section-header">
                <h2>Upcoming Appointments</h2>
            </div>
            <table class="appointments-table">
                <thead>
                    <tr>
                        <th>Status</th>
                        <th>ID</th>
                        <th>Client Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Service</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="status-indicator status-active"></span>Active</td>
                        <td>001</td>
                        <td>Sarah Johnson</td>
                        <td>2025-07-08</td>
                        <td>09:00 AM</td>
                        <td>Personal Training</td>
                    </tr>
                    <tr>
                        <td><span class="status-indicator status-pending"></span>Pending</td>
                        <td>002</td>
                        <td>Mike Davis</td>
                        <td>2025-07-08</td>
                        <td>10:30 AM</td>
                        <td>Fitness Assessment</td>
                    </tr>
                    <tr>
                        <td><span class="status-indicator status-active"></span>Active</td>
                        <td>003</td>
                        <td>Emily Brown</td>
                        <td>2025-07-08</td>
                        <td>02:00 PM</td>
                        <td>Nutrition Consultation</td>
                    </tr>
                    <tr>
                        <td><span class="status-indicator status-pending"></span>Pending</td>
                        <td>004</td>
                        <td>David Wilson</td>
                        <td>2025-07-08</td>
                        <td>04:30 PM</td>
                        <td>Group Class</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="charts-section fade-in">
            <div class="section-header">
                <h2>Monthly Overview</h2>
            </div>
            <div class="chart-container">
                <div class="chart-card">
                    <h3>Appointments This Month</h3>
                    <div class="chart-wrapper">
                        <canvas id="appointmentsChart"></canvas>
                    </div>
                    <p class="chart-description">Daily appointment bookings trend</p>
                </div>
                <div class="chart-card">
                    <h3>Revenue This Month</h3>
                    <div class="chart-wrapper">
                        <canvas id="feesChart"></canvas>
                    </div>
                    <p class="chart-description">Total fees collected daily</p>
                </div>
                <div class="chart-card">
                    <h3>New Registrations</h3>
                    <div class="chart-wrapper">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                    <p class="chart-description">New member registrations</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        const days = ['Jul 1', 'Jul 2', 'Jul 3', 'Jul 4', 'Jul 5', 'Jul 6', 'Jul 7'];
        const appointments = [5, 8, 12, 15, 10, 18, 12];
        const fees = [500, 800, 1200, 1500, 1000, 1800, 1200];
        const registrations = [2, 3, 1, 4, 2, 5, 3];

        setTimeout(() => {
            document.getElementById('loadingOverlay').style.display = 'none';
        }, 1000);

        function createChart(canvasId, label, data, borderColor, bgColor) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: days,
                    datasets: [{
                        label: label,
                        data: data,
                        borderColor: borderColor,
                        backgroundColor: bgColor,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        pointBackgroundColor: borderColor,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#e1e8ed',
                                borderDash: [3, 3]
                            },
                            ticks: {
                                stepSize: 1,
                                color: '#7f8c8d',
                                font: {
                                    size: 12,
                                    weight: '500'
                                },
                                callback: function(value) {
                                    if (Number.isInteger(value)) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#7f8c8d',
                                font: {
                                    size: 12,
                                    weight: '500'
                                }
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        createChart('appointmentsChart', 'Appointments', appointments, '#3498db', 'rgba(52, 152, 219, 0.1)');
        createChart('feesChart', 'Fees Collected', fees, '#27ae60', 'rgba(39, 174, 96, 0.1)');
        createChart('registrationsChart', 'Registrations', registrations, '#e74c3c', 'rgba(231, 76, 60, 0.1)');

        
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>