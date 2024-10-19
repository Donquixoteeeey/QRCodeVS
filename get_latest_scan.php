<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "qr_code_management";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}

// Fetch the latest user scan based on time_timestamp
$stmt = $pdo->query("SELECT user_id, time, time_timestamp, action_type FROM user_time_logs ORDER BY time_timestamp DESC LIMIT 1");
$latestScan = $stmt->fetch(PDO::FETCH_ASSOC);

echo json_encode($latestScan);
?>
