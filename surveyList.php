<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey List</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: sans-serif; text-align: center;}
    </style>
</head>
<body>


    <p><nav class="nav justify-content-center">
    <a href="welcome.php" class="nav-item nav-link active">Home</a>
    <a href="account-info.php" class="nav-item nav-link active">Account info</a>
    <a href="surveyList.php" class="nav-item nav-link">Complete surveys</a>
    <a href="order_page.php" class="nav-item nav-link">orders/checkout</a>
</nav>

<?php
    // Initialize the session
    session_start();
    
    // Check if the user is already logged in, if yes then redirect them to welcome page
    if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
        header("location: login.php");
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


  // Get the users username, currency, and ID
  $current_username = $_SESSION["username"];
  $getUserInfo = "SELECT currency, User_ID FROM User WHERE username = '$current_username'";
  $info = $conn->query($getUserInfo);
    //echo("Error description: " . $conn->error);
  $info = $info->fetch_assoc();
  $purse = $info["currency"];
  $userID = $info["User_ID"];
?>

<p><h1 align="center"> Poller </h1></p>

<p align="right"><font size="+1"><strong><?= $current_username?></strong>   $<?= $purse ?>    </font></p>



<p align="center"><br><u>List of Available Surveys</u></p>

<?php
    $currentSurvey = 1738; //setting random value in case of errors
    
    $surveyQuery = "SELECT Survey_ID, company_name, value FROM Surveys";
    $result = $conn->query($surveyQuery);
    //$checkCompletedQuery = "SELECT survey_taken from Completes WHERE user = $userID and survey_taken = $currentSurvey";
    echo "<form action='takeSurvey.php'>";
    if ($result->num_rows > 0) {
    // output data of each row
        while($row = $result->fetch_assoc()) {
            $currentSurvey = $row["Survey_ID"];
            $checkCompleted = $conn->query("SELECT survey_taken from Completes WHERE user = $userID and survey_taken = $currentSurvey");
            $checkCompleted = $checkCompleted->fetch_assoc();
            //echo $checkCompleted;
            $checkCompleted = $checkCompleted["survey_taken"];
            
            if($checkCompleted != $currentSurvey) { // if survey has not been taken already
                // Display radio button and survey info
                echo "<p align='center'><input type='radio' name='selection' value=$currentSurvey>";
                echo " ID: " . $row["Survey_ID"]. " - Company: " . $row["company_name"]. " - Value: " . $row["value"]."</p>";
            }
            
        }
            echo "<input type=submit class='btn btn-primary' value='Begin Selected Survey'>";
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
