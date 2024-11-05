<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't been started yet
}

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

include 'db_connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user info
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

    // Initialize limit and offset for pagination
    $limit = 20; // Number of entries per page
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
    $offset = ($page - 1) * $limit; // Calculate offset

    // Initialize the SQL query base and prepare to capture the additional date filtering if needed
    $date_filter_query = '';
    $date_filter_params = [];
    $param_types = 'i'; // Start with one integer parameter for user_id

    // Check if a date range has been selected
    if (isset($_GET['date_range'])) {
        $date_range = $_GET['date_range'];

        if ($date_range == '7_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 7 DAY";
        } elseif ($date_range == '30_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 30 DAY";
        } elseif ($date_range == '60_days') {
            $date_filter_query = " AND time_timestamp >= NOW() - INTERVAL 60 DAY";
        } elseif ($date_range == 'custom' && isset($_GET['start_date']) && isset($_GET['end_date'])) {
            // Get custom date range inputs
            $start_date = trim($_GET['start_date']);
            $end_date = trim($_GET['end_date']);
            
            // Debugging: Output the start and end dates
            // var_dump($start_date, $end_date); // Check if dates are correctly retrieved
            
            // Prepare the query with placeholders
            $date_filter_query = " AND time_timestamp BETWEEN ? AND ?";
            // Add date parameters for binding
            $date_filter_params[] = $start_date . ' 00:00:00'; // Start of the day
            $date_filter_params[] = $end_date . ' 23:59:59';   // End of the day
            // Update parameter types to include two string parameters
            $param_types .= 'ss'; // Add 'ss' for two string parameters
        }
        
    }

    // Modify the SQL query to include the date filter and pagination
    $activity_query = "SELECT time_timestamp, action_type FROM user_time_logs WHERE user_id = ? $date_filter_query LIMIT ? OFFSET ?";
    $stmt = $conn->prepare($activity_query);

    // Prepare the array for binding parameters
    $bind_params = [];
    if (!empty($date_filter_params)) {
        // Start with the parameter types for user_id
        $bind_params[] = &$user_id; // Reference to user_id

        // Add each date filter parameter
        foreach ($date_filter_params as &$param) {
            $bind_params[] = &$param; // Reference to each date parameter
        }

        // Add limit and offset
        $bind_params[] = &$limit; // Reference to limit
        $bind_params[] = &$offset; // Reference to offset

        // Prepare parameter types: 
        // 1 for user_id, ss for dates if present, and ii for limit and offset
        $param_types = 'i' . str_repeat('s', count($date_filter_params)) . 'ii';
        
        // Use call_user_func_array to bind parameters
        array_unshift($bind_params, $param_types); // Insert the type string at the start
        call_user_func_array([$stmt, 'bind_param'], $bind_params);
    } else {
        // If no custom dates are provided, just bind user_id, limit, and offset
        $stmt->bind_param("iii", $user_id, $limit, $offset);
    }

    // Execute the statement and fetch results
    $stmt->execute();
    $result = $stmt->get_result();

    $activities = [];
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }

    // Count total activities for pagination
    $count_query = "SELECT COUNT(*) as total FROM user_time_logs WHERE user_id = ? $date_filter_query";
    $count_stmt = $conn->prepare($count_query);

    // Initialize parameters for count query
    $bind_count_params = [$user_id]; // Start with user_id

    // If there are date filter parameters, bind them
    if (!empty($date_filter_params)) {
        // Append date filter parameters
        $bind_count_params = array_merge([$user_id], $date_filter_params);
    }

    // Create the appropriate parameter types
    $bind_count_types = 'i'; // Start with 'i' for user_id
    if (count($date_filter_params) > 0) {
        $bind_count_types .= str_repeat('s', count($date_filter_params)); // Add 's' for each date parameter
    }

    // Now bind the parameters dynamically
    $count_stmt->bind_param($bind_count_types, ...$bind_count_params); // Using splat operator to unpack the array

    // Execute the count statement
    $count_stmt->execute();
    $count_result = $count_stmt->get_result();
    $total_row = $count_result->fetch_assoc();
    $total_activities = $total_row['total'];
    $total_pages = ceil($total_activities / $limit); // Calculate total pages

    // Close the prepared statements
    $stmt->close();
    $user_stmt->close();
    $count_stmt->close();
} else {
    echo "No user ID provided.";
    exit;
}

// Close the connection at the end of the script
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activities</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            font-size: 2em;
            font-weight: 300;
            color: #333;
            text-align: center;
            margin-top: 30px;
            font-family: 'Comfortaa', cursive;
            color: #2C2B6D;
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
            text-decoration: none; /* Prevent underline */
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
.pagination {
    text-align: center;
    margin: 20px 0;
    margin-top: 25px;
}

.pagination a {
    display: inline-block;
    margin: 0 10px;
    padding: 10px 15px;
    font-size: 16px;
    color: #fff;
    background-color: #2C2B6D;
    border-radius: 20px;
    text-decoration: none;
    transition: background-color 0.3s;
    width: 120px; /* Set a fixed width for both buttons */
    text-align: center; /* Center the text */
}

.pagination a:hover {
    background-color: #0056b3;
}

.icon-spacing {
    margin-right: 10px; /* Adjust the value for more or less space */
}

.date-range-container {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 10px;
}

.date-range-select,
input[type="date"] {
    padding: 10px 15px;
    font-size: 16px;
    border: 2px solid #2C2B6D;
    border-radius: 15px;
    margin-right: 10px;
    margin-bottom: 20px;
    margin-top: 30px;
    transition: border-color 0.3s, box-shadow 0.3s;
    width: 200px; /* Set a fixed width for consistency */
    font-family: 'Inter', sans-serif; 
    font-size: 15px;
}

.date-range-select:focus,
input[type="date"]:focus {
    border-color: #4342a5; /* Darker border on focus */
    box-shadow: 0 0 5px rgba(66, 66, 205, 0.5); /* Subtle glow effect */
    outline: none; /* Remove default outline */
    font-family: 'Inter', sans-serif; 
   
}

.filter-button {
    padding: 11px 20px;
    font-size: 15px;
    color: #fff;
    background-color: #2C2B6D;
    border: none;
    border-radius: 15px;
    cursor: pointer;
    transition: background-color 0.3s;
    margin-left: 10px;
    margin-bottom: 10px;
    margin-top: 14px;
    
}

.filter-button:hover {
    background-color: #0056b3; /* Darker background on hover */
}

.date-label {
    margin-right: 10px; /* Space between the label and the input */
    margin-left: 10px;
}

    </style>
</head>
<body>
<a href="dashboard.php" class="back-button"><i class="fas fa-arrow-left icon-spacing"></i>Back to Dashboard</a>
<h1>User <?php echo htmlspecialchars($user_name); ?>'s Activity</h1>
<div class="user-info">
    <p>Vehicle: <?php echo htmlspecialchars($vehicle); ?></p>
    <p>Plate Number: <?php echo htmlspecialchars($plate_number); ?></p>
</div>

<form method="GET" style="margin-bottom: 20px;">
    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
    
    <div class="date-range-container""> 
    <select name="date_range" id="dateRangeSelect" class="date-range-select" onchange="toggleCustomDateInputs()">
    <option value="" <?php echo empty($_GET['date_range']) ? 'selected' : ''; ?>>Select Date Range</option>
    <option value="7_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '7_days') ? 'selected' : ''; ?>>Last 7 Days</option>
    <option value="30_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '30_days') ? 'selected' : ''; ?>>Last 30 Days</option>
    <option value="60_days" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === '60_days') ? 'selected' : ''; ?>>Last 60 Days</option>
    <option value="custom" <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === 'custom') ? 'selected' : ''; ?>>Custom Range</option>
</select>

        
        <div id="customDateInputs" style="display: <?php echo (isset($_GET['date_range']) && $_GET['date_range'] === 'custom') ? 'block' : 'none'; ?>; margin-top: 10px; display: inline-block;">
    <label for="start_date" class="date-label">Start Date:</label>
    <input type="date" name="start_date" id="start_date" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>" class="date-input">
    <label for="end_date" class="date-label">End Date:</label>
    <input type="date" name="end_date" id="end_date" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>" class="date-input">
</div>

        
        <button type="submit" class="filter-button">Filter</button>
    </div>
</form>




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

<div class="pagination">
    <?php if ($page > 1): ?>
        <a href="?user_id=<?php echo urlencode($user_id); ?>&page=<?php echo $page - 1; ?>" class="back-button">Previous</a>
    <?php endif; ?>
    
    <?php if ($page < $total_pages): ?>
        <a href="?user_id=<?php echo urlencode($user_id); ?>&page=<?php echo $page + 1; ?>" class="back-button">Next</a>
    <?php endif; ?>
</div>

<script>
   function toggleCustomDateInputs() {
    const dateRangeSelect = document.getElementById('dateRangeSelect');
    const customDateInputs = document.getElementById('customDateInputs');
    customDateInputs.style.display = dateRangeSelect.value === 'custom' ? 'inline-block' : 'none';
}

// Call the function on page load to set the initial state
window.onload = toggleCustomDateInputs;


</script>

</body>
</html>
