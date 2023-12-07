<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}



require_once "config.php";

$current_username = $_SESSION["username"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <title>Order Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: sans-serif; text-align: center;}

        .popup {
        visibility: hidden;
        position: center;
        display: inline-block;
        cursor: pointer;
        }

        /* The actual popup (appears on top) */
        .popup .popuptext {
        visibility: hidden;
        width: 160px;
        background-color: #555;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 8px 0;
        position: absolute;
        z-index: 1;
        bottom: 125%;
        left: 50%;
        margin-left: -80px;
        }

        /* Popup arrow */
        .popup .popuptext::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #555 transparent transparent transparent;
        }

        /* Toggle this class when clicking on the popup container (hide and show the popup) */
        .show {
        visibility: visible;
        -webkit-animation: fadeIn 1s;
        animation: fadeIn 1s
        }

        /* Add animation (fade in the popup) */
        @-webkit-keyframes fadeIn {
        from {opacity: 0;}
        to {opacity: 1;}
        }

        @keyframes fadeIn {
        from {opacity: 0;}
        to {opacity:1 ;}
        }
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

    $getUserInfo = "SELECT currency from User WHERE username = '$current_username'";
    $purse = $conn->query($getUserInfo);
    $purse = $purse->fetch_assoc();
    $purse = $purse["currency"];
?>
<p align="right"><strong><font size="+1"><?= $current_username?></strong>  $<?= $purse ?>    </font></p>

<?php 

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
function getUserCredits($conn, $current_username)
{
    //$credits = 0;
    //$stmt = $conn->prepare("SELECT currency FROM User WHERE username = '$current_username'");
    //$stmt->execute();
    //$result = $stmt->get_result();
    $credits = $conn->query("SELECT currency FROM User WHERE username = '$current_username'");
    $credits = $credits->fetch_assoc();
    $credits = $credits['currency'];
    /*if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $credits = $row['currency'];
    }*/

    //$stmt->close();
    return $credits;
}

// Read data from the database (using only the prizes table)
$prizes = getPrizes($conn);


// Display order page
echo '<h1>Order Page</h1>';


// Process order form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["prizes"])) {
    //echo "In form processing  ";
    // Get the user ID
    $user_id_query = "SELECT User_ID FROM User WHERE username = ?";
    $user_id_stmt = $conn->prepare($user_id_query);
    $user_id_stmt->bind_param("s", $current_username);
    if(!$user_id_stmt->execute()){
        echo "Order form error description: " . $conn->error;
    } //else { echo "Order form ran<br>";}
    $user_id_result = $user_id_stmt->get_result();
    $user_id_row = $user_id_result->fetch_assoc();
    $user_id = $user_id_row["User_ID"];

    // Get the user's current credits
    $current_credits = getUserCredits($conn, $current_username);
    //echo "got credits: $current_credits <br>";

    // Calculate the total cost of selected prizes
    $total_cost = 0;
    foreach ($_POST["prizes"] as $prize_id) {
        foreach ($prizes as $prize) {
            if ($prize["Prize_ID"] == $prize_id) {
                $total_cost += $prize["cost"];
                //break;
            }
        }
    }
    //echo "Total: $total_cost";

    // Check if the user has sufficient credits
    if ($current_credits >= $total_cost) {
        // Subtract the cost from the user's credits
        $new_credits = $current_credits - $total_cost;
        $update_credits_query = "UPDATE User SET currency = ? WHERE User_ID = ?";
        $update_credits_stmt = $conn->prepare($update_credits_query);
        $update_credits_stmt->bind_param("ii", $new_credits, $user_id);
        $update_credits_stmt->execute();

        // Insert orders into the Orders database
        $insert_order_query = "INSERT INTO Orders (Order_ID, User_ID, Prize_ID) VALUES (?, ?, ?)";   // Needs Order_ID column included in both (arguments) lists
        $insert_order_stmt = $conn->prepare($insert_order_query);
        $orderID;
        foreach ($_POST["prizes"] as $prize_id) {
            $insert_order_stmt->bind_param("iii", $orderID, $user_id, $prize_id);
            if(!$insert_order_stmt->execute()){
                echo "INSERT INTO Orders error description: " . $conn->error;
            }
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

echo '<div>';
echo '<button type="submit" action="placeOrder()">Place Order</button>';
//echo '<button onclick="showPopup(\'Cancel Order\')">Cancel Order</button>';
echo "<button onclick='cancelOrder()'>Cancel Order</button>";
echo '</div>';
//echo '<input type="submit" value="Submit Order">';

echo '</form>';

// Close the database connection
$conn->close();
?>

<div class="popup" onclick='closePopup(this.id)'>Click to close
  <span class="popuptext" id="placedPopup">Order successfully placed!</span>
</div>

<div class="popup" onclick='closePopup(this.id)'>Click to close
  <span class="popuptext" id="cancelledPopup">Order cancelled</span>
</div>



<!-- JavaScript for handling the popups -->
<script>
    function showPopup(type) {
        // Create a popup element
        var popup=document.getElementByID(type);
        popup.classlist.toggle("show");
        /*const newPopupDiv = document.createElement("div");
        const popupMsg = document.createTextNode(message);
        newPopupDiv.appendChild(popupMsg);
        //popup.innerHTML = '<p>' + message + '</p>';
        const currPopupDiv = document.getElementByID("div1");

        // Append the popup to the body
        document.body.insertBefore(newPopupDiv, currPopopDiv);

        // Close the popup after 2 seconds (adjust as needed)
        setTimeout(function () {
            document.body.removeChild(popupMsg);
        }, 2000);*/
    }
    function closePopup(type) {
        var popup=document.getElementByID(type);
        popup.classlist.toggle("show");
    }

    function placeOrder() {
        // Submit the form to process the order
        document.querySelector("form").submit(); 
        var popup=document.getElementByID("placedPopup");
        popup.classlist.toggle("show");
    }
    function cancelOrder() {
        document.querySelector("form").reset();
        let cancelledMsg = "Order cancelled";
        var popup=document.getElementByID("cancelledPopup");
        popup.classlist.toggle("show");
    }
</script>
</body>
</html>
