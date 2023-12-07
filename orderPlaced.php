<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Congratulations Page</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: sans-serif; text-align: center;}
        /* Style for buttons */
        button {
            padding: 10px;
            margin: 5px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #2980b9;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

    </style>
</head>
<h1 align="center">Poller</h1>
<body>

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
//echo $current_username;

$getUserInfo = "SELECT currency from User WHERE username = '$current_username'";
$purse = $conn->query($getUserInfo);
$purse = $purse->fetch_assoc();
$purse = $purse["currency"];

?>

<p align="right"><strong><font size="+1"><?= $current_username?>  $<?= $purse ?>    </font></strong></p>

<p align="center"> Order placed successfully! <p>
<div>
    <button action="order_page.php"> Return to orders page </button>
    <button action="takeSurvey.php"> Take a Survey </button>
    <button action="welcome.php"> Go to Home </button>
</div>
</body>
</html>