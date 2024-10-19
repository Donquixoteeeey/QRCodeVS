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
            transition: transform 0.3s ease-in-out, width 0.3s ease-in-out; /* Added width transition */
            font-family: 'Inter', sans-serif;
            
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
            transform: scale(1.10); /* Slightly increase size on hover */
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
            margin-left: 310px; /* Adjust this based on sidebar width */
            margin-top: 75px;
        }

        .collapsed .main-content {
            margin-left: 20px; /* Adjust for space when sidebar is collapsed */
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
            z-index: 1000;
            
        }

        .logo-container {
            text-align: center;
        }

        .logo-container img {
            width: 200px; /* Adjust logo size here */
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

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }

            .collapsed .sidebar {
                transform: translateX(-100%);
            }

            .collapsed .main-content {
                margin-left: 80px; /* Adjust for mobile */
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
                margin-left: 80px; /* Adjust for mobile */
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
    
        .dashboard-cards {
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    gap: 20px; /* This will add space between the cards */
}

.card {
    background-color: #ECECEC;
    border-radius: 15px;
    padding: 20px;
    width: 30%;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: transform 0.2s ease-in-out;
    margin-bottom: 20px; /* Optional: adds space below the card */
}


    .card:hover {
        transform: translateY(-10px);
    }

    .card-icon {
        font-size: 25px;
        color: #2C2B6D;
        margin-bottom: 28px;
    }

    .card-content h3 {
        font-family: 'Comfortaa', cursive;
        font-size: 18px;
        color: #2C2B6D;
        margin: 0;
        margin-left: 10px;
    }

    .card-content p {
        font-size: 24px;
        font-weight: bold;
        color: #000522;
        margin: 0;
        margin-top: 10px;
        margin-left: 10px;
    }

    @media (max-width: 768px) {
        .dashboard-cards {
            flex-direction: column;
            align-items: center;
        }

       
    }
    .tables-container {
    display: flex; /* Enables flexbox layout */
    justify-content: space-between; /* Space between the two tables */
    width: 100%; /* Full width of the parent container */
    font-family: 'Inter', sans-serif; 
}

.table-container {
    width: 45%; /* Each table container occupies 48% of the width */
    margin-top: 10px; /* Space above the containers */
    background-color: #fff; /* Background color for the container */
    border-radius: 15px; /* Rounded corners */
    padding: 20px; /* Padding inside the container */
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Shadow effect */
    margin-bottom: 20px;
}

.table-container h2 {
    font-family: 'Comfortaa', cursive; /* Consistent font */
    color: #2C2B6D; /* Heading color */
    margin-bottom: 20px; /* Space below the heading */
    margin-top: 10px;
    font-size: 22px; /* Larger font size for the heading */
}

/* Reuse the existing styles for the table */
.user-details-table,
.previously-scanned-table {
    width: 100%; /* Full width of the container */
    border-collapse: collapse; /* Collapses borders */
}

.user-details-table th,
.user-details-table td,
.previously-scanned-table th,
.previously-scanned-table td {
    padding: 15px; /* Padding for table cells */
    text-align: left; /* Align text to the left */
    border-bottom: 1px solid #ddd; /* Light bottom border for rows */
    border-top-left-radius: 10px; /* Rounded corners on the top left */
    border-top-right-radius: 10px; /* Rounded corners on the top right */
}

.user-details-table th,
.previously-scanned-table th {
    background-color: #2C2B6D; /* Light grey background for headers */
    font-weight: bold; /* Bold text for headers */
    color: #f1f1f1; /* Darker color for header text */
    font-size: 15px;
}





    </style>
</head>
<body>
    <button class="toggle-btn" onclick="toggleSidebar()">&#9776;</button>
    
    <div class="sidebar">
        <div class="logo-container">
            <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        </div>
        <a href="dashboard.php" class="highlighted"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
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
    <div class="dashboard-title">Dashboard</div>
    <div class="date-display"></div>

    <div class="dashboard-cards">
    <!-- Card for Registered Users -->
    <div class="card">
        <div class="card-icon"><i class="fas fa-users"></i></div>
        <div class="card-content">
            <h3>Registered Users</h3>
            <p id="total-users">Loading...</p> <!-- This will be updated dynamically -->
        </div>
    </div>

    <!-- New card for New Users This Week -->
    <div class="card">
        <div class="card-icon"><i class="fas fa-user-plus"></i></div> <!-- New icon -->
        <div class="card-content">
            <h3>New Users</h3>
            <p id="new-users-weekly">Loading...</p> <!-- This will be updated dynamically -->
        </div>
    </div>

    <!-- Card for Scanned Today -->
    <!-- Card for Total Scans Today -->
<div class="card">
    <div class="card-icon"><i class="fas fa-qrcode"></i></div> <!-- Use an icon for scans -->
    <div class="card-content">
        <h3>Total Scans Today</h3>
        <p id="totalScansToday">0</p> <!-- This will be updated dynamically -->
    </div>
</div>
    </div>

    <div class="tables-container">
    <div class="table-container">
        <h2>New Users</h2> <!-- New heading for the table -->
        <table class="user-details-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Vehicle</th>
                    <th>Registration Date</th>
                </tr>
            </thead>
            <tbody id="user-details-body">
                <!-- User data will be dynamically added here -->
            </tbody>
        </table>
    </div>

    <div class="table-container">
    <h2>Previously Scanned</h2> <!-- New heading for the table -->
    <table class="previously-scanned-table">
        <thead>
            <tr>
                <th>QR Code Details</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="previously-scanned-body">
            <!-- User data will be dynamically added here -->
        </tbody>
    </table>
</div>

</div>



    <script>

        
document.addEventListener('DOMContentLoaded', function() {
    // Fetch new users from the PHP script
    fetch('get_new_users.php') // Adjust path if necessary
        .then(response => response.json())
        .then(data => {
            const userDetailsBody = document.getElementById('user-details-body');
            userDetailsBody.innerHTML = ''; // Clear existing rows

            // Loop through each user and create a new row
            data.forEach(user => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${user.name}</td>
                    <td>${user.vehicle}</td>
                    <td>${new Date(user.registration_date).toLocaleDateString()}</td>
                `;
                userDetailsBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching new users:', error));
});

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

    document.addEventListener('DOMContentLoaded', function() {
        fetch('get_total_users.php') // Replace with the actual path to your PHP file
            .then(response => response.text())
            .then(data => {
                document.querySelector('#total-users').textContent = data;
            })
            .catch(error => console.error('Error:', error));
    });

    fetch('get_new_users_weekly.php')  // Path to your new PHP file
    .then(response => response.text())
    .then(data => {
        document.getElementById('new-users-weekly').textContent = data;
    })
    .catch(error => console.error('Error fetching new users:', error));

    function fetchTotalScansToday() {
    fetch('get_total_scans.php') // Assuming you created this PHP file
    .then(response => response.json())
    .then(data => {
        const totalScansToday = data.totalScansToday;
        document.getElementById('totalScansToday').textContent = totalScansToday;

    })
    .catch(error => console.error('Error fetching total scans:', error));
}

// Call this function when the page loads
document.addEventListener('DOMContentLoaded', () => {
    fetchTotalScansToday();
});


    </script>
</body>
</html>
