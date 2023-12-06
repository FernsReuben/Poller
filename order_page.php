<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Page</title>

    <!-- Add a simple CSS style for the popups -->
    <style>
    .popup {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        padding: 20px;
        background-color: #fff;
        border: 1px solid #ddd;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        border-radius: 8px;
        z-index: 1000;
        max-width: 300px;
        text-align: center;
    }

    .popup p {
        margin: 0;
    }

    /* Style for buttons */
    button {
        padding: 10px;
        margin: 5px;
        background-color: #3498db;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    button:hover {
        background-color: #2980b9;
    }
    </style>
</head>
<body>
<?php
// Function to read CSV file and return data as an associative array
function readCSV($filename)
{
    $rows = file_exists($filename) ? array_map('str_getcsv', file($filename)) : [];
    if (empty($rows)) {
        // Handle the case where the file is not found or empty
        return [];
    }

    $header = array_shift($rows);
    $csvData = [];

    foreach ($rows as $row) {
        $csvData[] = array_combine($header, $row);
    }

    return $csvData;
}

// Read Orders.csv and Prizes.csv
$orders = readCSV('Orders.csv');
$prizes = readCSV('Prizes.csv');

// Display order page
echo '<h1>Order Page</h1>';
echo '<table border="1">';
echo '<tr><th>Order ID</th><th>User ID</th><th>Prize</th><th>Quantity</th><th>Total Cost</th><th>Actions</th></tr>';

foreach ($orders as $order) {
    // ... (Your existing foreach loop)

    // Add buttons for actions
    echo '<td>';
    echo '<button onclick="showPopup(\'Order Placed!\')">Place Order</button>';
    echo '<button onclick="showPopup(\'Order Canceled!\')">Cancel Order</button>';
    echo '</td>';

    echo '</tr>';
}

echo '</table>';
?>

<!-- JavaScript for handling the popups -->
<script>
    function showPopup(message) {
        // Create a popup element
        var popup = document.createElement('div');
        popup.className = 'popup';
        popup.innerHTML = '<p>' + message + '</p>';

        // Append the popup to the body
        document.body.appendChild(popup);

        // Close the popup after 2 seconds (adjust as needed)
        setTimeout(function() {
            document.body.removeChild(popup);
        }, 2000);
    }
</script>

</body>
</html>