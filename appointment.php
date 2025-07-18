<?php
session_start();

$conn = new mysqli('localhost', 'root', '', 'fitnesszone');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function getAvailableSlots($conn, $date)
{
    $allSlots = [
        '10:00 AM',
        '10:30 AM',
        '11:00 AM',
        '11:30 AM',
        '12:00 PM',
        '12:30 PM',
        '1:00 PM',
        '1:30 PM',
        '2:00 PM',
        '2:30 PM',
        '3:00 PM',
        '3:30 PM'
    ];

    $bookedSlots = [];

    $stmt = $conn->prepare("SELECT time FROM Appointment WHERE date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $bookedSlots[] = $row['time'];
    }

    $stmt->close();

    return array_diff($allSlots, $bookedSlots);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'book') {
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $stmt = $conn->prepare("INSERT INTO Appointment (name, date, time) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $date, $time);

    if ($stmt->execute()) {
        $message = "Your appointment has been booked. Our staff will call to confirm later today.";
    } else {
        $message = "Error booking appointment. Please try again.";
    }

    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'get_slots') {
    $date = $_POST['date'];
    $availableSlots = getAvailableSlots($conn, $date);
    echo json_encode(array_values($availableSlots));
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Elite Fitness</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
                        url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') center/cover no-repeat fixed;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
        }

        /* Back Button Styles */
        .back-btn {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(255, 107, 53, 0.1);
            color: #ff6b35;
            border: 1px solid #ff6b35;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            z-index: 20;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .back-btn:hover {
            background: #ff6b35;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.4);
        }

        .back-btn::before {
            content: "← ";
            margin-right: 4px;
            font-size: 14px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 35px;
            margin-top: 20px;
        }

        .form-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .form-header p {
            color: #666;
            font-size: 14px;
            font-weight: 400;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e1e5e9;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 400;
            color: #333;
            background: #fff;
            transition: all 0.2s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #ff6b35;
            box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
        }

        .form-group input::placeholder {
            color: #999;
            font-weight: 400;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .message {
            padding: 14px 16px;
            margin-bottom: 24px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
            font-size: 14px;
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        /* Loading state */
        .loading {
            color: #666;
            font-style: italic;
        }

        .error {
            color: #dc3545;
            font-style: italic;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .form-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .form-header h2 {
                font-size: 24px;
            }

            .back-btn {
                top: 12px;
                left: 12px;
                padding: 6px 12px;
                font-size: 11px;
            }
        }
    </style>
</head>

<body>
    <div class="form-container">
        <!-- Back Button -->
        <button class="back-btn" onclick="goBack()">Back</button>

        <div class="form-header">
            <h2>Book Appointment</h2>
            <p>Schedule your training session with our professional trainers</p>
        </div>

        <?php if (!empty($message)) {
            echo "<div class='message'>$message</div>";
        } ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" placeholder="Enter your full name" required>
            </div>

            <div class="form-group">
                <label for="date">Appointment Date</label>
                <input type="date" name="date" id="date" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="time">Available Time</label>
                <select name="time" id="time" required>
                    <option value="">Select time slot</option>
                </select>
            </div>

            <input type="hidden" name="action" value="book">
            <button type="submit" class="submit-btn">Book Appointment</button>
        </form>
    </div>

    <script>
        // Back button functionality
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback if no history exists
                window.location.href = 'index.php'; // Change to your homepage
            }
        }

        document.getElementById('date').addEventListener('change', function() {
            const date = this.value;
            const timeSelect = document.getElementById('time');
            
            // Show loading state
            timeSelect.innerHTML = '<option value="" class="loading">Loading available slots...</option>';
            timeSelect.disabled = true;
            
            fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'get_slots',
                        date: date
                    })
                })
                .then(response => response.json())
                .then(data => {
                    timeSelect.innerHTML = '<option value="">Select time slot</option>';
                    timeSelect.disabled = false;

                    if (data.length === 0) {
                        timeSelect.innerHTML = '<option value="" class="error">No slots available</option>';
                        return;
                    }

                    data.forEach(slot => {
                        const option = document.createElement('option');
                        option.value = slot;
                        option.textContent = slot;
                        timeSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error("Error:", error);
                    timeSelect.innerHTML = '<option value="" class="error">Error loading slots</option>';
                    timeSelect.disabled = false;
                });
        });
    </script>
</body>

</html>