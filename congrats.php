<html>
<h1 align="center">Poller</h1>
<body>
	<p> Congrats! You earned <?= $_GET['ptsEarned']?> pts! </p>
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
	$getUserID = "SELECT User_ID FROM User WHERE username = $current_username";
	$userID = $conn->query($getUserID);
	$surVal = $_GET['ptsEarned'];
	$answers = "";
	$j = 1;
	echo $_GET["choice$j"];
	
	for ($i=1; $i <= ($surVal/10); $i++) {
		$choice = $_GET["choice$i"];
		//echo $choice;
		$answers .= "choice$i=" . $choice;
		if ($i != $surVal/10) $answers .= "&";
	}
	echo $answers;

	$surveyID = $_GET["surveyTaken"];
	//echo "<p><font size='+1'>Thanks! You earned <strong>$surVal</strong> points!</font></p>";
	//$updateCompleted = "INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (Completes_ID, 111111, :surveyID, :answers)";
	$completesStmt = $conn->prepare("INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (?, ?, ?, ?)");
	$completesID;
	$completesStmt->bind_param('iiss', $completesID, $userID, $surveyID, $answers);
	//echo "Params bound";
	if (!$completesStmt->execute()){
		echo("Error description: " . $conn->error);
	}
	echo "<br><br>Hey completes insert worked!";

	//$currencyStmt = ;
	if (!$conn->query("UPDATE Users SET currency = (currency+$surVal) WHERE User_ID = $userID")){
		echo("Error description: " . $conn->error);
	}
	$conn->close();
?>
 
</body>
</html>