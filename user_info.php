<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php'); // Redirect to login page
    exit();
}

include 'db_connect.php'; 
$searchTerm = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $searchTerm = $_POST['search'];
}

$sql = "SELECT id, name, vehicle, plate_number, contact_number, qr_code_path FROM user_info WHERE name LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%{$searchTerm}%"; 
$stmt->bind_param("s", $searchTerm); 
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Istok+Web&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        $(document).ready(function(){
            function loadUserData(searchTerm = '') {
                $.ajax({
                    type: 'POST',
                    url: 'search.php',
                    data: { search: searchTerm },
                    success: function(response){
                        $('#search-results').html(response);
                    }
                });
            }

            loadUserData();

            $('#search-input').on('input', function(){
                var searchTerm = $(this).val();
                loadUserData(searchTerm);
            });
        });

    </script>

    <style>

        body {
            margin: 0;
            font-family: 'Istok Web', sans-serif;
            display: flex;
            height: 100vh;
            background-color: #f0f0f0;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar {
            width: 300px;
            background-color: #ECECEC;
            color: #000522;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 20px 20px 0 0;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            transition: transform 0.3s ease-in-out;
            font-family: 'Inter', sans-serif;
            z-index: 1000; 
        }

        .collapsed .sidebar {
            transform: translateX(-100%);
        }

        .sidebar img {
            width: 250px;
            margin: 20px 0;
            transition: margin 0.3s;
        }

        .sidebar a {
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            margin: 10px 0;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar a i {
            margin-right: 30px;
            margin-left: 10px;
            width: 10px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            color: #f0f0f0;
            transform: scale(1.10); 
        }

        .sidebar .highlighted {
            background-color: #2C2B6D;
            color: #f0f0f0;
        }

        .sidebar .logout {
            margin-top: auto;
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            transition: background-color 0.3s, color 0.3s;
        }

        .sidebar .logout:hover {
            background-color: #0056b3;
            color: #f0f0f0;
        }

        .date-display {
            font-size: 14px;
            color: #787272;
            margin-top: 10px;
            text-align: left;
            font-family: 'Comfortaa', cursive;
            margin-left: 0;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            transition: margin-left 0.3s;
            margin-left: 310px; 
            margin-top: 75px;
        }

        .collapsed .main-content {
            margin-left: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            font-family: 'Comfortaa', cursive;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            width: 30px;
            height: 30px;
            background-color: #2C2B6D;
            color: #fff;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            z-index: 2000;
        }

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            width: 200px; 
            height: auto;
        }

        .header-icons {
            position: fixed;
            top: 15px;
            right: 20px;
            display: flex;
            gap: 20px;
            z-index: 1000;
        }

        .admin-profile {
            width: 40px;
            height: 40px;
            background-color: #d3d3d3;
            color: #000522;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 10px;
        }

        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            margin-top: 30px;
            height: 550px; 
            overflow-x: auto; 
            overflow-y: auto; 
        }
        
        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            margin-top: 30px;
            height: 480px; 
            overflow: hidden; 
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Inter', sans-serif; 
        }
        
        thead th {
            background-color: #2C2B6D;
            color: #f1f1f1;
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        tbody {
            display: block;
            height: 320px; 
            overflow-y: auto; 
        }
        
        tr {
            display: table;
            width: 100%;
            table-layout: fixed; 
        }
        
        td, th {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th, td {
            width: 20%; 
        }
        
        thead th:first-child {
            border-top-left-radius: 15px;
            border-top-right-radius: 0px;
        }
        
        thead th:last-child {
            border-top-right-radius: 15px;
            border-top-left-radius: 0px;
        }
        
        thead th:not(:first-child):not(:last-child) {
            border-radius: 0;
        }

        th {
            background-color: #2C2B6D;
            color: #f1f1f1;
            border-radius: 15px 15px 0px 0;
        }

        tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        .search-container {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 20px;
            margin-top: 10px;
            margin-right: 40px;
        }

        .search-container input[type="text"] {
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 20px 0 0 20px;
            width: 200px;
            font-family: 'Inter', sans-serif; 
        }

        .search-container .btn-search {
            padding: 8px 16px;
            font-size: 14px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 0 20px 20px 0;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-family: 'Inter', sans-serif; 
        }

        .search-container .btn-search:hover {
            background-color: #0056b3;
        }

        .btn {
            display: inline-block;
            padding: 6px 12px;
            margin: 0 5px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-edit {
            background-color: #007bff; 
            border-radius: 20px;
            font-family: 'Inter', sans-serif; 
        }

        .btn-delete {
            background-color: #dc3545; 
            border-radius: 20px;
            font-family: 'Inter', sans-serif; 
        }

        .btn-edit:hover {
            background-color: #0056b3;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-add {
            display: inline-block;
            padding: 8px 16px;
            margin: 0 0 20px;
            border: none;
            border-radius: 5px;
            background-color: #28a745; 
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            font-family: 'Inter', sans-serif; 
            border-radius: 20px;
        }

        .btn-add:hover {
            background-color: #218838;
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }

            .collapsed .sidebar {
                transform: translateX(-100%);
            }

            .collapsed .main-content {
                margin-left: 80px; 
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 200px;
            }

            .collapsed .sidebar {
                transform: translateX(-100%);
            }

            .collapsed .main-content {
                margin-left: 80px; 
            }
        }

        .admin-profile {
            width: 40px;
            height: 40px;
            background-color: #d3d3d3;
            color: #000522;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            position: relative; 
        }
        
        .admin-profile:hover {
            background-color: #b0b0b0;
        }
        
        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            margin-top: 5px;
        }
        
        .dropdown a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: #3a3a3a;
            font-size: medium;
        }
        
        .dropdown a:hover {
            background-color: #ddd;
            border-radius: 15px;
        }
        
        .admin-profile:hover .dropdown {
            display: block;
        }
    
    </style>

</head>

<body>

    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
    
    <div class="sidebar">
        <div class="logo-container">
            <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        </div>
        <a href="dashboard.php" ><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php" class="highlighted"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php" ><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <div class="header-icons">
        
        <div class="admin-profile">
            A
            <div class="dropdown">
                <a href="login.php">Logout</a>
            </div>
        </div>
    </div>

    <div class="main-content">
    <div class="dashboard-title">User Information</div>
    <div class="date-display"></div>

   <div class="table-container">
    <div class="search-container">
        <input type="text" id="search-input" placeholder="Search Users...">
        <button type="submit" class="btn-search"><i class="fas fa-search"></i> Search</button>
    </div>
    <a href="add_user.php" class="btn-add"><i class="fas fa-plus"></i> Add User</a>
    <table>
        <thead>
            <tr>
                <th class="name-column">Name</th>
                <th class="vehicle-column">Vehicle</th>
                <th class="plate-number-column">Plate Number</th>
                <th class="contact-number-column">Contact Number</th>
                <th class="actions-column">Actions</th>
            </tr>
        </thead>
        <tbody id="search-results">
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['vehicle']); ?></td>
                <td><?php echo htmlspecialchars($row['plate_number']); ?></td>
                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                <td>
                    <a href="edit_user.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                    <a href="delete_user.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn-delete"><i class="fas fa-trash"></i> Delete</a>
                    <a href="view_qr.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn-view"><i class="fas fa-qrcode"></i> View QR Code</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>

        function toggleSidebar() {
            document.body.classList.toggle('collapsed');
        }

        const profile = document.querySelector('.admin-profile');
        const dropdown = document.querySelector('.dropdown');

        profile.addEventListener('click', () => {
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!profile.contains(event.target)) {
                dropdown.style.display = 'none';
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
           
            const dateDisplay = document.querySelector('.date-display'); const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }; 
            const today = new Date(); dateDisplay.textContent = today.toLocaleDateString('en-US', options); })

    </script>
    
</body>

</html>
