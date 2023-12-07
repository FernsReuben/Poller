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
    $sql = "SELECT * FROM Prizes";
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
        echo "Error executing query: " . $conn->error;
        return [];
    }
}

// Function to get the user's current credits
function getUserCredits($conn, $username)
{
    $credits = 0;
    $stmt = $conn->prepare("SELECT currency FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $credits = $row['currency'];
    }

    $stmt->close();
    return $credits;
}

// Read data from the database (using only the prizes table)
$prizes = getPrizes($conn);


// Display order page
echo '<h1>Order Page</h1>';

// Header buttons
echo '<div>';
echo '<button onclick="placeOrder()">Place Order</button>';
echo '<button onclick="showPopup(\'Cancel Order\')">Cancel Order</button>';
echo '</div>';

// Process order form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["prizes"])) {
    // Get the user ID
    $current_username = $_SESSION["username"];
    $user_id_query = "SELECT User_ID FROM Users WHERE username = ?";
    $user_id_stmt = $conn->prepare($user_id_query);
    $user_id_stmt->bind_param("s", $current_username);
    $user_id_stmt->execute();
    $user_id_result = $user_id_stmt->get_result();
    $user_id_row = $user_id_result->fetch_assoc();
    $user_id = $user_id_row["User_ID"];

    // Get the user's current credits
    $current_credits = getUserCredits($conn, $current_username);

    // Calculate the total cost of selected prizes
    $total_cost = 0;
    foreach ($_POST["prizes"] as $prize_id) {
        foreach ($prizes as $prize) {
            if ($prize["Prize_ID"] == $prize_id) {
                $total_cost += $prize["cost"];
                break;
            }
        }
    }

    // Check if the user has sufficient credits
    if ($current_credits >= $total_cost) {
        // Subtract the cost from the user's credits
        $new_credits = $current_credits - $total_cost;
        $update_credits_query = "UPDATE Users SET currency = ? WHERE User_ID = ?";
        $update_credits_stmt = $conn->prepare($update_credits_query);
        $update_credits_stmt->bind_param("ii", $new_credits, $user_id);
        $update_credits_stmt->execute();

        // Insert orders into the Orders database
        $insert_order_query = "INSERT INTO Orders (User_ID, Prize_ID) VALUES (?, ?)";
        $insert_order_stmt = $conn->prepare($insert_order_query);

        foreach ($_POST["prizes"] as $prize_id) {
            $insert_order_stmt->bind_param("ii", $user_id, $prize_id);
            $insert_order_stmt->execute();
        }

        // Close the prepared statements
        $update_credits_stmt->close();
        $insert_order_stmt->close();
    } else {
        // User doesn't have sufficient credits, display an error message or handle it accordingly
        echo '<script>alert("Insufficient credits to place the order.");</script>';
    }

    // Close the user ID statement
    $user_id_stmt->close();
}

echo '<form action="order_page.php" method="post">'; // Assuming you will process the order on the same page

echo '<table>';
echo '<tr><th>Name</th><th>Cost</th><th>Select</th></tr>';

foreach ($prizes as $prize) {
    // Display prize details with checkboxes
    echo '<tr>';
    echo '<td>' . $prize['name'] . '</td>';
    echo '<td>' . $prize['cost'] . '</td>';
    echo '<td><input type="checkbox" name="prizes[]" value="' . $prize['Prize_ID'] . '"></td>';
    echo '</tr>';
}

echo '</table>';

echo '<input type="submit" value="Submit Order">';

echo '</form>';

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

    function placeOrder() {
        // Submit the form to process the order
        document.querySelector("form").submit();
    }
</script>
</body>
</html>
