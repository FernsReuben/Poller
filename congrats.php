<html>
<h1 align="center">Poller</h1>
<body>
	<p align='center'> Congrats! You earned <?= $_GET['ptsEarned']?> pts! </p>
<?php 
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect them to welcome page
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
    // Create connection
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    // Check connection
    if ($conn->connect_error) {
    	die("Connection failed: " . $conn->connect_error);
    }

	$current_username = $_SESSION["username"];
	$getUserID = "SELECT User_ID FROM User WHERE username = '$current_username'";
	$userID = $conn->query($getUserID);
	$userID = $userID->fetch_assoc();
	$userID = $userID["User_ID"];

	$surVal = $_GET['ptsEarned'];
	$answers = "";
	//$j = 1;
	//echo $_GET["choice$j"];
	
	for ($i=1; $i <= ($surVal/10); $i++) {
		$choice = $_GET["choice$i"];
		//echo $choice;
		$answers .= "choice$i=" . $choice;
		if ($i != $surVal/10) $answers .= "&";
	}
	//echo $answers;

	$surveyID = $_GET["surveyTaken"];
	//echo "<p><font size='+1'>Thanks! You earned <strong>$surVal</strong> points!</font></p>";
	//$updateCompleted = "INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (Completes_ID, 111111, :surveyID, :answers)";
	$completesStmt = $conn->prepare("INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (?, ?, ?, ?)");
	$completesID;
	$completesStmt->bind_param('iiss', $completesID, $userID, $surveyID, $answers);
	//echo "Params bound";
	if (!$completesStmt->execute()){
		echo("Error description 1: " . $conn->error);
	}
	//echo "<br><br>Hey completes insert worked!";
	
	// Setting new currency value
	$getUserInfo = "SELECT currency from User WHERE username = '$current_username'";
    $purse = $conn->query($getUserInfo);
    $purse = $purse->fetch_assoc();
    $purse = $purse["currency"];
	$purse = $purse + $surVal;
	echo "<p align='center'> You now have $$purse at your disposal!!! <p>";

	$currencyUpdate = "UPDATE User SET currency = $purse WHERE User_ID = $userID";
	
	if (!$conn->query($currencyUpdate)){
		echo("Error description 2: " . $conn->error);
	}
	$conn->close();
?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
			body{ font: 14px sans-serif; text-align: center; }
	</style>
	<a href="welcome.php" class="btn btn-warning">Go to Home</a>
	<a href="surveyList.php" class="btn btn-warning">Return to Surveys</a>
	<a href="order_page.php" class="btn btn-warning">Order Some Prizes</a>
</body>
</html>

<!--
		<button class="button button1" align="center" href="surveyList.php">  </button>
		<button class="button button1" align="center" href="order_page.php">  </button>
-->