<?php
// fetch_time_logs.php

$host = 'localhost'; // Change these values to match your database credentials
$dbname = 'your_database_name';
$username = 'your_username';
$password = 'your_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all activity logs from your database
    $stmt = $pdo->query("SELECT user_id, time_in, time_out FROM user_time_logs");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the logs as JSON
    echo json_encode($logs);

} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
