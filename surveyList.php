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
  
?>

<p><h1 align="center"> Poller </h1></p>

<p align="right"><strong><font size="+1"><?= $username, $purse ?></font></strong></p>



<p><br><u>List of All Surveys:</u></p>

<?php

    $surveyQuery = "SELECT Survey_ID, company_name, value FROM Surveys";
    $result = $conn->query($surveyQuery);
    $checkCompletedQuery = "SELECT Survey_ID from Completes WHERE User_ID = $username and Survey_ID = $currentSurvey";

    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $row["Survey_ID"]. " - Company: " . $row["company_name"]. " - Value: " . $row["value"]."<br>";
            $currentSurvey = $row["Survey_ID"];
            $checkCompleted = $conn->query($checkCompletedQuery);
            if($checkCompleted["Survey_ID"] == $currentSurvey) { //Survey has been taken already
                echo "<button> Survey Taken </button><br>";
            } else {                                             //Survey has not yet been taken
                echo "<button onclick=window.location.href='https://dbdev.cs.kent.edu/~tbaker60/Poller/takeSurvey.php';> Take Survey </button><br>";
            }
        }
    } else {
        echo "0 results";
    }

   $conn->close();
?> <!-- this is the end of our php code -->
</body>

</html>
