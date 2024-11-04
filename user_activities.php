<?php

include 'db_connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $query = "SELECT time_timestamp, action_type FROM user_time_logs WHERE user_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch the results
    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    // Close the statement and connection
    $stmt->close();
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
        /* Add your CSS styles here */
    </style>
</head>
<body>
    <h1>User Activities for User ID: <?php echo htmlspecialchars($user_id); ?></h1>
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
                    <td colspan="2">No activities found for this user.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($activities as $activity): ?>
                    <tr>
                        <td><?php echo date("Y-m-d H:i:s", strtotime($activity['time_timestamp'])); ?></td>
                        <td><?php echo $activity['action_type']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
