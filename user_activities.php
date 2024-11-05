<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

include 'db_connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    
    $user_query = "SELECT name, vehicle, plate_number FROM user_info WHERE id = ?";
    $user_stmt = $conn->prepare($user_query);
    $user_stmt->bind_param("i", $user_id);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    
    if ($user_result->num_rows > 0) {
        $user_row = $user_result->fetch_assoc();
        $user_name = $user_row['name'];
        $vehicle = $user_row['vehicle'];
        $plate_number = $user_row['plate_number'];
    } else {
        echo "User not found.";
        exit;
    }

    
    $activity_query = "SELECT time_timestamp, action_type FROM user_time_logs WHERE user_id = ?";
    $stmt = $conn->prepare($activity_query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    $stmt->close();
    $user_stmt->close();
    $conn->close();
} else {
    echo "No user ID provided.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activities</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }
        h1 {
            font-size: 1.8em;
            font-weight: 600;
            color: #333;
            text-align: center;
            margin-top: 30px;
            font-family: 'Inter', sans-serif;
        }
      
        table {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            overflow: hidden;
            border-radius: 8px;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #2C2B6D;
            color: #fff;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #eaf3ff;
        }
        tbody td {
            font-family: 'Inter', sans-serif;
            color: #555;
        }
        tbody td:first-child {
            font-family: 'Inter', sans-serif;
            font-weight: 500;
            color: #333;
        }
        .empty-state {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
        }
        @media (max-width: 600px) {
            table, th, td {
                font-size: 14px;
            }
            h1 {
                font-size: 1.5em;
            }
        }
        .back-button {
            display: inline-block;
            margin: 20px 0 20px 80px;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #2C2B6D;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            text-align: center;
        }
        .back-button:hover {
            background-color: #4342a5;
        }
        .user-info {
    text-align: center;
    margin-bottom: 20px;
    color: #555;
    font-size: 1.1em;
    padding: 10px; 
    margin: 20px auto; 
    max-width: 600px;
}
    </style>
</head>
<body>
<button class="back-button" onclick="history.back()">Back</button>
    <h1>User <?php echo htmlspecialchars($user_name); ?>'s Activity (User ID: <?php echo htmlspecialchars($user_id); ?>)</h1>
    <div class="user-info">
        <p>Vehicle: <?php echo htmlspecialchars($vehicle); ?></p>
        <p>Plate Number: <?php echo htmlspecialchars($plate_number); ?></p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Date/Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($activities)): ?>
                <tr>
                    <td colspan="2" class="empty-state">No activities found for this user.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?php echo date("Y-m-d H:i:s", strtotime($activity['time_timestamp'])); ?></td>
                        <td><?php echo htmlspecialchars($activity['action_type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
