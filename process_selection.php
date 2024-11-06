<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'config.php';  // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_users'])) {
    $selectedUsers = $_POST['selected_users'];

    if (!empty($selectedUsers)) {
        $ids = implode(",", array_map('intval', $selectedUsers));  // Sanitize the user IDs
        $query = "SELECT name, plate_number, qr_code_url FROM user_info WHERE id IN ($ids)";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            echo "<div class='qr-codes-to-print'>";
            $counter = 0;  // Counter to limit QR codes per row
            while ($row = $result->fetch_assoc()) {
                if ($counter % 4 == 0 && $counter != 0) {
                    echo "</div><div class='qr-codes-to-print'>";  // Start a new row after every 4 QR codes
                }
                echo "<div class='qr-item'>";
                echo "<p>{$row['name']}</p>";
                echo "<img src='{$row['qr_code_url']}' alt='QR Code' style='width: 150px; height: 150px;' />";
                echo "<p>Plate Number: {$row['plate_number']}</p>";
                echo "</div>";
                $counter++;
            }
            echo "</div>";  // End the last .qr-codes-to-print div
            echo "<div class='print-button-container'>
            <button class='cancel-button' onclick='cancelPrint()'>Cancel</button>
                    <button class='print-button' onclick='printSelectedQRCodes()'>Print Selected QR Codes</button>
                    
                  </div>";
        } else {
            echo "No QR codes found for selected users.";
        }
    } else {
        echo "No users selected.";
    }
}
$conn->close();
?>

<script type="text/javascript">
    function printSelectedQRCodes() {
        window.print();  // This will print the current page
    }

    function cancelPrint() {
        // Redirect the user back to the QR code management page
        window.location.href = "qr_code_management.php";  // Change this to your actual page URL
    }
</script>

<style>
    body{
        font-family: Inter, sans-serif;
        font-weight: 200;
    }
    /* Normal styling for the webpage */
    .qr-codes-to-print {
        display: grid;
        grid-template-columns: repeat(4, 1fr);  /* 4 columns per row */
        gap: 20px;  /* Space between items */
        margin-bottom: 20px;
    }

    .qr-item {
        text-align: center;
    }

    .qr-item img {
        max-width: 100%;
        height: auto;
    }

    /* Center the button container */
    .print-button-container {
        display: flex;
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
        margin-top: 20px; /* Space from the QR codes */
        padding: 20px 0;  /* Padding around the button */
    }

    /* Button Styling */
    .print-button, .cancel-button {
        background-color: #2C2B6D; /* Green background for the Print button */
        color: white;  /* White text */
        padding: 10px 0;  /* Vertical padding */
        font-size: 15px;  /* Font size */
        border: none;  /* Remove border */
        border-radius: 15px;  /* Rounded corners */
        cursor: pointer;  /* Pointer cursor on hover */
        transition: background-color 0.3s ease;  /* Smooth transition for background color */
        width: 210px;  /* Set both buttons to be the same width */
        margin: 10px; /* Space between buttons */
    }

    /* Cancel button additional styling */
    .cancel-button {
        background-color: #6c757d;
    }

    .cancel-button:hover {
        background-color: #5a6268;
    }

    .print-button:hover {
        background-color: #0056b3;/* Darker green on hover */
    }

    /* This will apply only when printing */
    @media print {
        /* Hide the print button during printing */
        .print-button, .cancel-button {
            display: none;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Inter, sans-serif; /* Optional: Adjust font for printing */
            font-weight: 200;
        }

        .qr-codes-to-print {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 QR codes per row */
            gap: 10px;
            width: 100%;
            padding: 10px;
        }

        .qr-item {
            text-align: center;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .qr-item img {
            width: 100%;
            height: auto;
            max-width: 150px; /* Adjust size as needed */
        }
    }
</style>
