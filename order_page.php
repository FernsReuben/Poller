<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
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

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
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

// Function to fetch data from the prizes table
function getPrizes($conn)
{
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

// Read data from the database (using only the prizes table)
$prizes = getPrizes($conn);

// Display navigation buttons
echo '<nav class="nav justify-content-center">';
echo '<a href="welcome.php" class="nav-item nav-link active">Home</a>';
echo '<a href="account-info.php" class="nav-item nav-link active">Account info</a>';
echo '<a href="faculty-table.php" class="nav-item nav-link">Complete surveys</a>';
echo '<a href="faculty-search.php" class="nav-item nav-link">orders/checkout</a>';
echo '</nav>';

// Display order page
echo '<h1>Order Page</h1>';

// Header buttons
echo '<div>';
echo '<button onclick="showPopup(\'Place Order\')">Place Order</button>';
echo '<button onclick="showPopup(\'Cancel Order\')">Cancel Order</button>';
echo '</div>';

echo '<table>';
echo '<tr><th>Prize ID</th><th>Name</th><th>Cost</th></tr>';

foreach ($prizes as $prize) {
    // Display prize details
    echo '<tr>';
    echo '<td>' . $prize['Prize_ID'] . '</td>';
    echo '<td>' . $prize['name'] . '</td>';
    echo '<td>' . $prize['cost'] . '</td>';
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
        setTimeout(function () {
            document.body.removeChild(popup);
        }, 2000);
    }
</script>

</body>
</html>
