<html>
<body>

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

    // This part will need updated based on our login implementation
    $getUserInfo = "SELECT currency from User WHERE User_ID = $username";
    $purse = $conn->query($getUserInfo);
    $surveyID = $_GET['selection'];
    $loadSurvey = "SELECT company_name, questions, value FROM Surveys WHERE Survey_ID = $surveyID";
    $result = $conn->query($loadSurvey);
    $survey = $result->fetch_assoc();
    $company = $survey["company_name"];
    $surVal = $survey["value"];
?>

<p><h1 align="center"> Poller </h1></p>
<p align="right"><strong><font size="+1"><?= $username, $purse ?></font></strong></p>

    <p align="center"><strong><?= $company ?> Survey:</strong> For each statement, enter the degree to which you agree from 0 to 5</p>
	<!--<form action="surveyList.php" method=get>
		<br>
        <br><input type=text size=1 name="choice1"><br>
        <br><input type=text size=1 name="choice1"><br>
        <br><input type=text size=1 name="choice1"><br>
		<input align="center" type="submit" value="submit" onclick= "window.location.href='https://dbdev.cs.kent.edu/~tbaker60/Poller/surveyList.php';">
		<input type="hidden" name="form_submitted" value="1" >
	</form>-->

<?php 
    $Qarray = array($surVal/10);
    parse_str($survey["questions"],$Qarray);
    $i=1;
    foreach ($Qarray as $question){
        echo "Q$i: $question <input type=text size=1 name='choice'><br><br>";
        $i++;
    }

    $conn->close();
?>
 
</body>
</html>