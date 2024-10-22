<?php
// Get QR code text from the request
$input = json_decode(file_get_contents("php://input"), true);
$qr_code_text = isset($input['qr_code_text']) ? $input['qr_code_text'] : '';

// Match and extract data using regex
$pattern = '/Name:\s*(.*?)\s*\|\s*Vehicle:\s*(.*?)\s*\|\s*Plate Number:\s*(.*)/';
if (preg_match($pattern, $qr_code_text, $matches)) {
    $name = $matches[1];
    $vehicle = $matches[2];
    $plate_number = $matches[3];
} else {
    // If the pattern doesn't match, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid QR code format']);
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed']));
}

// Check if QR code data exists in user_info
$sql = "SELECT id, name, vehicle, plate_number FROM user_info WHERE name = ? AND vehicle = ? AND plate_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sss', $name, $vehicle, $plate_number);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($user) {
    // User found
    echo json_encode(['success' => true, 'user' => $user]);
} else {
    // No match found
    echo json_encode(['success' => false, 'message' => 'User not found']);
}
?>
