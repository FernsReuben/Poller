<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
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
    <a href="faculty-table.php" class="nav-item nav-link">Complete surveys</a>
    <a href="faculty-search.php" class="nav-item nav-link">orders/checkout</a>
</nav>

<p><h2>User accont information:</h2></p>

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

   $sql = "SELECT id, name, address, currency FROM USER";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
     	// Setup the table and headers
	echo "<Center><table><tr><th>ID</th><th>Name</th><th>Address</th><th>Currency</th></tr>";
	// output data of each row into a table row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td> ".$row["address"]."</td><td> ".$row["currency"]."</td></tr>";
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
