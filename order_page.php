<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

$current_username = '';

?>
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
// Database configuration
$servername = "localhost";
$username = "kguzy";
$password = "s39SwfTz";
$dbname = "kguzy";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch data from the orders table
function getOrders($conn) {
    $sql = "SELECT * FROM orders";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        return $orders;
    } else {
        return [];
    }
}

// Function to fetch data from the prizes table
function getPrizes($conn) {
    $sql = "SELECT * FROM prizes";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Select specific attributes from the row
            $prizes[] = [
                'Prize_ID' => $row['Prize_ID'],
                'name' => $row['name'],
                'cost' => $row['cost'],
            ];
        }
        return $prizes;
    } else {
        return [];
    }
}

// Read data from the database
$orders = getOrders($conn);
$prizes = getPrizes($conn);

// Display order page
echo '<h1>Order Page</h1>';
echo '<table border="1">';
echo '<tr><th>Order ID</th><th>User ID</th><th>Prize</th><th>Quantity</th><th>Total Cost</th><th>Actions</th></tr>';

foreach ($orders as $order) {
    $prizeID = $order['Prize_ID'];
    $quantity = intval($order['quantity']);

    // Find the prize details based on Prize_ID
    $prizeDetails = array_filter($prizes, function ($prize) use ($prizeID) {
        return $prize['Prize_ID'] == $prizeID;
    });

    // Get the first (and only) element from the filtered array
    $prizeDetails = reset($prizeDetails);

    // Display order details
    echo '<tr>';
    echo '<td>' . $order['Order_ID'] . '</td>';
    echo '<td>' . $order['User_ID'] . '</td>';
    echo '<td>' . $prizeDetails['name'] . '</td>';
    echo '<td>' . $quantity . '</td>';

    // Calculate total cost for the order
    $totalCost = $quantity * $prizeDetails['cost'];
    echo '<td>' . $totalCost . '</td>';

    // Add buttons for actions
    echo '<td>';
    echo '<button onclick="showPopup(\'Order Placed!\')">Place Order</button>';
    echo '<button onclick="showPopup(\'Order Canceled!\')">Cancel Order</button>';
    echo '</td>';

    echo '</tr>';
}

echo '</table>';

// Close the database connection
$conn->close();
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
