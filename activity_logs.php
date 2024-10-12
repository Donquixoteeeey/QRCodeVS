<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
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
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .sidebar img {
            width: 250px;
            margin-bottom: 30px;
        }

        .sidebar a {
            width: 100%;
            color: #787272;
            text-decoration: none;
            font-size: 16px;
            margin: 10px 0;
            padding: 10px;
            border-radius: 30px 0 30px 0;
            display: flex;
            align-items: center;
            box-sizing: border-box;
        }

        .sidebar a i {
            margin-right: 25px;
            margin-left: 10px;
        }

        .sidebar a:hover {
            background-color: #0056b3;
            color: #f0f0f0;
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
            box-sizing: border-box;
        }

        .sidebar .logout i {
            margin-right: 25px;
        }

        .sidebar .logout:hover {
            background-color: #0056b3;
            color: #f0f0f0;
        }

        .header-icons {
            position: fixed;
            top: 15px;
            right: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            z-index: 1000;
        }

        .notification-bell,
        .settings-icon,
        .admin-profile {
            font-size: 18px;
            cursor: pointer;
            color: #787272;
        }

        .separator {
            height: 26px;
            border-left: 1px solid #3a3a3a;
            background-color: #0000FF;
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
        }

        .admin-profile:hover {
            background-color: #b0b0b0;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 60px;
            right: 0;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            border-radius: 30px;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            margin-left: 20px;
        }

        .dashboard-title {
            font-size: 24px;
            font-weight: bold;
            color: #2C2B6D;
            margin-bottom: 10px;
            margin-left: 20px;
            margin-top: 50px;
            font-family: 'Comfortaa', cursive;
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .card h3 {
            margin: 0 0 20px;
            font-size: 30px;
            color: #000522;
            font-family: 'Comfortaa', cursive;
        }

        .video-container {
            position: relative;
            width: 100%;
            height: 400px;
            display: none; /* Hide by default */
            margin-top: 20px;
        }

        .video-container video {
            width: 100%;
            height: 100%;
            border-radius: 10px;
        }

        .output {
            margin-top: 20px;
            font-size: 20px;
            color: #2C2B6D;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #2C2B6D;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
            }

            .sidebar img {
                width: 150px;
            }

            .sidebar a {
                font-size: 14px;
            }

            .header-icons {
                right: 10px;
                gap: 15px;
            }

            .dashboard-title {
                font-size: 20px;
                margin-bottom: 10px;
            }

            .main-content {
                margin-left: 250px;
            }
        }

        @media (max-width: 480px) {
            .sidebar {
                width: 200px;
            }

            .sidebar img {
                width: 100px;
            }

            .sidebar a {
                font-size: 12px;
            }

            .header-icons {
                right: 5px;
                gap: 10px;
            }

            .dashboard-title {
                font-size: 18px;
                margin-bottom: 5px;
            }

            .main-content {
                margin-left: 200px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <img src="img/QR CODE VERIFICATION SYSTEM LOGO.png" alt="Admin Dashboard Logo">
        <a href="dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_info.php"><i class="fas fa-users"></i> User Information</a>
        <a href="qr_code_management.php"><i class="fas fa-qrcode"></i> QR Code Management</a>
        <a href="activity_logs.php" class="highlighted"><i class="fas fa-clipboard-list"></i> Activity Logs</a>
        <a href="login.php" class="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
    <div class="header-icons">
        <i class="fas fa-bell notification-bell"></i>
        <div class="separator"></div>
        <i class="fas fa-cog settings-icon"></i>
        <div class="admin-profile">
            <span>A</span>
            <div class="dropdown">
                <a href="login.php">Logout</a>
            </div>
        </div>
    </div>
    <div class="main-content">
        <div class="dashboard-title">Activity Logs</div>
        
        <div class="card">
            <h3>Scan QR Code</h3>
            <button id="scanButton" style="padding: 10px 20px; font-size: 18px; border-radius: 10px; border: none; background-color: #000522; color: #f0f0f0;">Start Camera</button>
            <div class="video-container" id="videoContainer">
                <video id="video" autoplay></video>
            </div>
            <div class="output" id="output"></div>
            <table id="timeLogTable">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Time In</th>
                        <th>Time Out</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Logged time entries will be added here dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://unpkg.com/@zxing/library@latest"></script>
    <script>
        const timeLogs = {}; // Object to keep track of user time logs
        const scanButton = document.getElementById('scanButton');
        const videoContainer = document.getElementById('videoContainer');
        const output = document.getElementById('output');
        const timeLogTable = document.getElementById('timeLogTable').getElementsByTagName('tbody')[0];

        scanButton.addEventListener('click', startCamera);

        function startCamera() {
            videoContainer.style.display = 'block'; 
            const codeReader = new ZXing.BrowserQRCodeReader();
            codeReader.decodeFromVideoDevice(null, 'video', (result, err) => {
                if (result) {
                    logTime(result.text);
                }
                if (err && !(err instanceof ZXing.NotFoundException)) {
                    console.error(err);
                }
            });
        }

        function logTime(userId) {
    const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

    if (!timeLogs[userId]) {
        // Time In
        timeLogs[userId] = { timeIn: currentTime, timeOut: null };

        const newRow = timeLogTable.insertRow();
        newRow.insertCell(0).textContent = userId;         
        newRow.insertCell(1).textContent = currentTime;     
        newRow.insertCell(2).textContent = "Not yet logged out"; 
        
        
        fetch('store_time.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ userId: userId, timeIn: currentTime })
        });

        output.textContent = `User ${userId} logged in at ${currentTime}`;
    } else {
        
        if (timeLogs[userId].timeOut === null) { 
            timeLogs[userId].timeOut = currentTime;

            // Update the Time Out in the existing row
            const rows = timeLogTable.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                if (rows[i].cells[0].textContent === userId) {
                    rows[i].cells[2].textContent = timeLogs[userId].timeOut; 
                    
                    // AJAX request to store Time Out
                    fetch('store_time.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ userId: userId, timeOut: currentTime })
                    });

                    output.textContent = `User ${userId} logged out at ${currentTime}`;
                    break; // Exit the loop once the row is found and updated
                }
            }
        } else {
            output.textContent = `User ${userId} has already logged out at ${timeLogs[userId].timeOut}`;
        }
    }
}

    </script>
</body>
</html>
