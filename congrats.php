<html>
<h1 align="center">Poller</h1>
<body>
	<p> Congrats! You earned <?= $_GET['ptsEarned']?> pts! </p>
<?php 
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

	$ansArr = array();
	$answers = $_GET["choice"];
	parse_str($_GET["choice"], $ansArr);
	echo count($ansArr);
	foreach($ansArr as $ans){
		echo $ans;
		$answers .= "ans=";
		$answers .= $ans;
		$answers .= "&";
	}

	$surveyID = $_GET["surveyTaken"];
	//echo "<p><font size='+1'>Thanks! You earned <strong>$surVal</strong> points!</font></p>";
	//$updateCompleted = "INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (Completes_ID, 111111, :surveyID, :answers)";
	$stmt = $conn->prepare("INSERT INTO Completes(Completes_ID, user, survey_taken, answers) VALUES (?, ?, ?, ?)");
	$userID = 123123;
	$completesID = 7;
	$stmt->bind_param('iiss', $completesID, $userID, $surveyID, $answers);
	echo "Params bound";
	if (!$stmt->execute()){
		echo("Error description: " . $conn->error);
	}
	echo "<br><br>Hey it worked!";
	echo "$answers";

	$conn->close();
?>
 
</body>
</html>