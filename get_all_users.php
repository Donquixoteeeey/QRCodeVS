<?php
// get_all_users.php

header('Content-Type: application/json');

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qr_code_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, vehicle, plate_number FROM user_info"; // Adjust the column names as needed
$result = $conn->query($sql);

$users = [];

if ($result->num_rows > 0) {
    
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
$conn->close();
?>
