<?php
header('Content-Type: application/json');

$host = 'localhost'; 
$db = 'qr_code_management';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? null;
$password = $data['password'] ?? null;

$query = "SELECT username, password FROM admins WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    if ($password === $row['password']) {
        
        $redirectUrl = ($username === 'admin2') ? 'phone_activity_logs.php' : 'dashboard.php';
        echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Username not found']);
}

$stmt->close();
$conn->close();
?>
