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
  
  if ($_GET["ptsEarned"]) {
    $earned = $_GET["ptsEarned"];
    echo "<h2 align='center'><font size='-1'> Congrats! You earned <strong>$earned</strong> points!</font><h2>"; 
  }
?>

<p><h1 align="center"> Poller </h1></p>

<p align="right"><strong><font size="+1"><?= $username, $purse ?></font></strong></p>



<p><br><u>List of All Surveys:</u></p>

<?php
    $currentSurvey = 1738;
    $taking = 0;
    //$takeSurveyButton = "<button onclick=window.location.href='https://dbdev.cs.kent.edu/~tbaker60/Poller/takeSurvey.php';> Take Survey </button><br>";

    $surveyQuery = "SELECT Survey_ID, company_name, value FROM Surveys";
    $result = $conn->query($surveyQuery);
    $checkCompletedQuery = "SELECT Survey_ID from Completes WHERE User_ID = $username and Survey_ID = $currentSurvey";
    echo "<form action='takeSurvey.php'>";
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            $currentSurvey = $row["Survey_ID"];
            $checkCompleted = $conn->query($checkCompletedQuery);
            if($checkCompleted["Survey_ID"] == $currentSurvey) { //Survey has been taken already
                echo "<button onclick='<p>That survey cannot be taken again</p>'> Survey Taken </button><br>";
            } else {                                             //Survey has not yet been taken
                echo "<input type='radio' name='selection' value=$currentSurvey>";
                    //
                    //onclick=window.location.href='https://dbdev.cs.kent.edu/~tbaker60/Poller/takeSurvey.php?currentSurvey';
            }
            echo "ID: " . $row["Survey_ID"]. " - Company: " . $row["company_name"]. " - Value: " . $row["value"]."<br>";
        }
            echo "<input type=submit value='Begin Selected Survey'>";
    } else {
        echo "0 results";
    }

    $conn->close();


    /*if (isset($_GET['selection'])){
        $_GET['selection'];
    }*/
?> <!-- this is the end of our php code -->
</body>

</html>
