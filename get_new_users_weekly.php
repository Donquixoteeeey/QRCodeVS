<?php

$servername = "localhost";
$username = "root";  // or 'admin' with correct credentials
$password = "";
$dbname = "qr_code_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to count the number of new users registered in the last 7 days
$sql = "SELECT COUNT(*) as new_users_weekly FROM user_info WHERE registration_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
$result = $conn->query($sql);

$new_users_weekly = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $new_users_weekly = $row['new_users_weekly'];
}

$conn->close();

// Return the number of new users registered in the last week
echo $new_users_weekly;
?>
