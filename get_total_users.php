<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to count the total number of users
$sql = "SELECT COUNT(*) as total_users FROM user_info"; 
$result = $conn->query($sql);

$total_users = 0;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_users = $row['total_users'];
}

$conn->close();

// Return the total number of users
echo $total_users;
?>
