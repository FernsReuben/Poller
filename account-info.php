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
    <title>Account Info</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		table, th, td { border: 1px solid black; }
    </style>
</head>
<body>
<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to Poller.</h1>
    <p><nav class="nav justify-content-center">
    <a href="welcome.php" class="nav-item nav-link active">Home</a>
    <a href="account-info.php" class="nav-item nav-link active">Account info</a>
    <a href="surveyList.php" class="nav-item nav-link">Complete surveys</a>
    <a href="order_page.php" class="nav-item nav-link">orders/checkout</a>
</nav>

<p><h2>User account information:</h2></p>

<?php // this line starts PHP Code
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


   $current_username = $_SESSION["username"];

   $sql = "SELECT User_ID, first_name, last_name, street_number, street_name, city, state, zip, email, currency FROM User WHERE username = ? ";

   $stmt = $conn->prepare($sql);

    // Bind the parameter
    $stmt->bind_param("s", $current_username);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();
    


   if ($result->num_rows > 0) {
        // Setup the table and headers
    echo "<Center><table><tr><th>ID</th><th>First name</th><th>Last name</th><th>Street number</th><th>Street name</th><th>City</th><th>State</th><th>Zip Code</th><th>Email</th><th>Currency</th></tr>";
    // output data of each row into a table row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["User_ID"]."</td><td>".$row["first_name"]."</td><td>".$row["last_name"]."</td><td>".$row["street_number"]."</td><td>".$row["street_name"]."</td><td>".$row["city"]."</td><td>".$row["state"]."</td><td>".$row["zip"]."</td><td>".$row["email"]."</td><td>".$row["currency"]."</td></tr>";
}

	echo "</table></center>"; // close the table
	echo "There are ". $result->num_rows . " results.";
	// Don't render the table if no results found
   	} else {
               echo "0 results";
               }
     $conn->close();

?> <!-- this is the end of our php code -->
<p> 
    <p>
        <a href="change-address.php" class="btn btn-warning">Change Address</a>
    </p>

</body>
</html>
