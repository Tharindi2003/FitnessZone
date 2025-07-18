<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'fitnesszone');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT u_membershipid FROM user ORDER BY u_membershipid DESC LIMIT 1";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastMembershipId = $row['u_membershipid'];
    $numericPart = intval(substr($lastMembershipId, 1));
    $newMembershipId = 'M' . str_pad($numericPart + 1, 5, '0', STR_PAD_LEFT);
} else {
    $newMembershipId = 'M00001';
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO user (u_membershipid, u_firstname, u_lastname, u_age, u_dob, u_gender, u_email, u_password)
            VALUES ('$newMembershipId', '$firstname', '$lastname', '$age', '$dob', '$gender', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully.<br>";

        $insertQuery = "
            INSERT INTO users (member_id, password, name)
            SELECT 
                u_membershipid AS member_id, 
                u_password AS password, 
                CONCAT(u_firstname, ' ', u_lastname) AS name
            FROM user
            WHERE u_membershipid = '$newMembershipId';
        ";

        if ($conn->query($insertQuery) === TRUE) {
            echo "User data transferred to 'users' table successfully.";
            header("Location: login.php");
            exit();
        } else {
            echo "Error transferring data to 'users' table: " . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitness Zone - Member Registration</title>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
    transition: all 0.3s ease-in-out;
}

body {
    background-color: #000;
    color: #fff;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background-image: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48');
    background-size: cover;
    background-position: center;
    position: relative;
}

body::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    z-index: 1;
    backdrop-filter: blur(4px);
}

    .registration-container {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 700px;
        padding: 48px;
        background: rgba(20, 20, 20, 0.9);
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.7);
        border: 1px solid #1f1f1f;
    }

    /* Back to Home Button */
        .back-home-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            background: rgba(255, 255, 255, 0.1);
            color: #ff4500;
            border: 1px solid #ff4500;
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
        }

        .back-home-btn:hover {
            background: #ff4500;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(255, 69, 0, 0.4);
        }

        .back-home-btn::before {
            content: "← ";
            margin-right: 4px;
        }


    .header {
        text-align: center;
        margin-bottom: 40px;
    }

    .brand-logo {
        font-size: 32px;
        font-weight: 800;
        color: #ff4500;
    }

    .brand-tagline {
        font-size: 15px;
        color: #bbb;
        margin-top: 4px;
    }

    .divider {
        width: 60px;
        height: 3px;
        margin: 24px auto;
        background: linear-gradient(to right, #ff4500, #ff6b35);
        border-radius: 3px;
    }

    .form-grid {
        display: grid;
        gap: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    label {
        font-size: 13px;
        color: #ccc;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    input,
    select {
        background: #111;
        color: #fff;
        padding: 14px 18px;
        border: 1px solid #333;
        border-radius: 8px;
        font-size: 15px;
    }

    input:focus,
    select:focus {
        border-color: #ff4500;
        outline: none;
        box-shadow: 0 0 0 2px rgba(255, 69, 0, 0.3);
    }

    input::placeholder {
        color: #777;
    }

    .gender-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .gender-option input[type="radio"] {
        display: none;
    }

    .gender-option label {
        background: #111;
        padding: 12px;
        text-align: center;
        border: 1px solid #333;
        border-radius: 8px;
        color: #ccc;
        font-weight: 500;
        cursor: pointer;
    }

    .gender-option input:checked + label {
        background-color: #ff4500;
        color: #fff;
        border-color: #ff4500;
    }

    .password-container {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        top: 50%;
        right: 16px;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #aaa;
        font-size: 16px;
        cursor: pointer;
    }

    .password-toggle:hover {
        color: #ff4500;
    }

    .membership-info {
        background: #1a1a1a;
        padding: 14px;
        border-left: 4px solid #ff4500;
        border-radius: 6px;
        margin-top: 6px;
    }

    .membership-label {
        font-size: 12px;
        color: #888;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .membership-id {
        font-family: 'Courier New', monospace;
        font-size: 15px;
        font-weight: bold;
        color: #ff6b35;
    }

    .submit-btn {
        background: linear-gradient(to right, #ff4500, #ff6b35);
        color: #fff;
        border: none;
        padding: 16px;
        font-size: 15px;
        font-weight: 700;
        border-radius: 8px;
        width: 100%;
        cursor: pointer;
        margin-top: 24px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    .submit-btn:hover {
        box-shadow: 0 8px 20px rgba(255, 69, 0, 0.4);
        transform: translateY(-2px);
    }

    .submit-btn::before {
        content: '';
        position: absolute;
        left: -100%;
        top: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .submit-btn:hover::before {
        left: 100%;
    }

    .form-footer {
        text-align: center;
        margin-top: 24px;
        border-top: 1px solid #222;
        padding-top: 16px;
    }

    .form-footer p {
        font-size: 13px;
        color: #aaa;
    }

    .form-footer a {
        color: #ff4500;
        text-decoration: none;
        font-weight: 600;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }

/* Loading Spinner */
.loading {
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-top-color: #fff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-right: 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .registration-container {
        padding: 32px 24px;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .registration-container {
        padding: 24px 16px;
    }

    .gender-container {
        grid-template-columns: 1fr;
    }
}


    </style>
</head>

<body>
    <div class="registration-container">

    <a href="index.html" class="back-home-btn">Back to Home</a>

        <div class="header">
            <div class="brand-logo">FITNESS ZONE</div>
            <div class="brand-tagline">Transform Your Body, Transform Your Life</div>
            <div class="divider"></div>
        </div>

        <form method="POST" action="" id="registrationForm">
            <div class="form-grid">
                <div class="form-row">
                    <div class="form-group">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" id="firstname" placeholder="John" required>
                    </div>
                    <div class="form-group">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" id="lastname" placeholder="Doe" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="age">Age</label>
                        <input type="number" name="age" id="age" placeholder="25" min="16" max="100" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth</label>
                        <input type="date" name="dob" id="dob" required>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label>Gender</label>
                    <div class="gender-container">
                        <div class="gender-option">
                            <input type="radio" name="gender" id="male" value="Male" required>
                            <label for="male">Male</label>
                        </div>
                        <div class="gender-option">
                            <input type="radio" name="gender" id="female" value="Female" required>
                            <label for="female">Female</label>
                        </div>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="john.doe@example.com" required>
                </div>

                <div class="form-group full-width">
                    <label for="membershipid">Membership ID</label>
                    <div class="membership-info">
                        <div class="membership-label">Auto-Generated Member ID</div>
                        <div class="membership-id" id="membershipDisplay">EF-2024-001</div>
                    </div>
                    <input type="hidden" name="membershipid" id="membershipid" value="<?php echo $newMembershipId; ?>">
                </div>

                <div class="form-group full-width">
                    <label for="password">Password</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Create a secure password" required minlength="8">
                        <button type="button" class="password-toggle" onclick="togglePassword()">👁️</button>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn" id="submitBtn">
                Create Account
            </button>
        </form>

        <div class="form-footer">
            <p>Already have an account? <a href="login.php">Sign in here</a></p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleBtn.textContent = '🙈';
            } else {
                passwordInput.type = 'password';
                toggleBtn.textContent = '👁️';
            }
        }
        
        // Auto-calculate age from DOB
        document.getElementById('dob').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            const age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            
            if (age > 0 && age < 150) {
                document.getElementById('age').value = age;
            }
        });

        // Generate membership ID display
        function generateMembershipId() {
            const prefix = 'EF';
            const year = new Date().getFullYear();
            const random = Math.floor(Math.random() * 9999) + 1;
            const id = `${prefix}-${year}-${random.toString().padStart(4, '0')}`;
            document.getElementById('membershipDisplay').textContent = id;
            document.getElementById('membershipid').value = id;
        }

        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<span class="loading"></span>Creating Account...';
            submitBtn.disabled = true;
        });

        generateMembershipId();

        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-group');
            formElements.forEach((element, index) => {
                element.style.opacity = '0';
                element.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>

</html>