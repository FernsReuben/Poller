<html>
<body>

<?php
    $servername = "localhost";
    $username = "kguzy";
    $password = "X";
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

    echo "<form>"; //Don't think I want the action to redirect
    $i=1;
    foreach ($Qarray as $question){
        echo "Q$i: $question <input type=text size=1 name='choice'><br><br>";
        $i++;
    }
    echo "<input type=submit value='Submit Answers'>";
    //echo "<input type='hidden' name='ptsEarned' value=$surVal>";
    //echo "<input type='hidden' name='form_submitted' value=$surVal/10>";
    echo "</form>";

    //page redirect should probably be implemented down here
    if (isset($_GET["form_submitted"]))
    {  
        echo "Is this part working?";
        $answers = $_GET["choice"];
        //echo "<p><font size='+1'>Thanks! You earned <strong>$surVal</strong> points!</font></p>";
        $updateCompleted = "INSERT INTO Completes(Completes_ID, User_ID, Survey_ID, answers) VALUES (NEWID(), $username, $surveyID, $answers)";
        $conn->query($updateCompleted);
        header("Location: surveyList.php?$surval");
    }

    
    $conn->close();
?>
 
</body>
</html>