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
    <title>Order Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: sans-serif; text-align: center;}
    </style>
</head>
<body>

    <p><nav class="nav justify-content-center">
    <a href="welcome.php" class="nav-item nav-link active">Home</a>
    <a href="account-info.php" class="nav-item nav-link active">Account info</a>
    <a href="surveyList.php" class="nav-item nav-link">Complete surveys</a>
    <a href="order_page.php" class="nav-item nav-link">orders/checkout</a>
</nav>
    <!-- Add a simple CSS style for the popups -->

    <div id="successModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p>Order placed successfully!</p>
    </div>
    </div>
    
    <style>
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 300px;
        text-align: center;
        border-radius: 8px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
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
        $insert_order_query = "INSERT INTO Orders (User_ID, Prize_ID) VALUES (?, ?)";   // Needs Order_ID column included in both (arguments) lists
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
    // Function to open the modal
    function openModal() {
        document.getElementById('successModal').style.display = 'block';
    }

    // Function to close the modal
    function closeModal() {
        document.getElementById('successModal').style.display = 'none';
    }

    function placeOrder() {
        // Submit the form to process the order
        document.querySelector("form").submit();

        // Open the success modal
        openModal();
    }
</script>
</body>
</html>
