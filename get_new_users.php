<?php
// Database connection parameters
$servername = "localhost"; // Change if necessary
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "qr_code_management"; // Your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to get the latest 7 users
$sql = "SELECT name, vehicle, registration_date FROM user_info ORDER BY registration_date DESC LIMIT 5";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row; // Store each user as an associative array
    }
}

// Return the JSON response
header('Content-Type: application/json');
echo json_encode($users);

// Close the connection
$conn->close();
?>
