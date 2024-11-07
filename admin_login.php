<?php
session_start(); // Start the session at the beginning of the PHP script
header('Content-Type: application/json'); // Set header for JSON response

$host = 'localhost'; 
$db = 'qr_code_management';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Database connection failed']));
}

// Check if the request method is POST for AJAX login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $username = $data['username'] ?? null;
    $password = $data['password'] ?? null;

    // Make the username comparison case-sensitive
    $query = "SELECT username, password FROM admins WHERE BINARY username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Replace this with proper password verification (e.g., using password_hash)
        if ($password === $row['password']) {
            // Set session variable
            $_SESSION['loggedin'] = true;

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
    exit; // Stop further execution after handling the AJAX request
}
?>
