<?php
// Database credentials
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'fitnesszone';

// Connect to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch additional coaches
$sql = "SELECT c_firstname, c_lastname, c_email, c_gender FROM coach LIMIT 4 OFFSET 4";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    $coaches = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $coaches[] = $row;
    }
    echo json_encode($coaches);
} else {
    echo json_encode([]);
}

=mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Test PHP and Database</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Database Test Result</h1>
  <p><?php echo $message; ?></p>
  <a href="index.html">Go Back</a>
</body>
</html>
