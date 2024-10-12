<?php
header('Content-Type: application/json');

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "qr_code_management"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get the JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['userId']) && isset($data['timeIn'])) {
    // Prepare to insert Time In
    $userId = $conn->real_escape_string($data['userId']);
    $timeIn = $conn->real_escape_string($data['timeIn']);
    
    $sql = "INSERT INTO user_time_logs (user_id, time_in) VALUES ('$userId', '$timeIn')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Time In recorded successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} elseif (isset($data['userId']) && isset($data['timeOut'])) {
    // Prepare to update Time Out
    $userId = $conn->real_escape_string($data['userId']);
    $timeOut = $conn->real_escape_string($data['timeOut']);
    
    $sql = "UPDATE user_time_logs SET time_out='$timeOut' WHERE user_id='$userId' AND time_out IS NULL";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "message" => "Time Out recorded successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input."]);
}

$conn->close();
?>
