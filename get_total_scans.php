<?php
// Database connection parameters
$host = "localhost";  // Use $host instead of $servername
$user = "root";      // Use $user instead of $username
$pass = "";
$db = "qr_code_management";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Query to get total scans for today using the 'timestamp' column
$date = date('Y-m-d');
$stmt = $pdo->prepare("SELECT COUNT(*) AS totalScans FROM user_time_logs WHERE DATE(time_timestamp) = :date");
$stmt->execute(['date' => $date]);

$totalScans = $stmt->fetch(PDO::FETCH_ASSOC);

// Return the total scans as JSON
echo json_encode(['totalScansToday' => $totalScans['totalScans']]);
?>
