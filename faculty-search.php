<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
include '/home/wreed6/phpconfig/config.inc';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Faculty</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		    table, th, td { border: 1px solid black; }
        input[type=text] { width: 15%; padding: 12px 20px; margin: 8px 0;  box-sizing: border-box;}
        input[type=button], input[type=submit], input[type=reset] { background-color: #04AA6D;  border: none;  color: white;  padding: 16px 32px;  text-decoration: none;  margin: 4px 2px;  cursor: pointer;}
    </style>
</head>
<body>
<h1 class="my-5">Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to the University Information System.</h1>
    <p><nav class="nav justify-content-center">
    <a href="welcome.php" class="nav-item nav-link active">Home</a>
    <a href="faculty-table.php" class="nav-item nav-link">View Faculty</a>
    <a href="faculty-search.php" class="nav-item nav-link">Search Faculty</a>
    <a href="newfaculty.php" class="nav-item nav-link">Enter New Faculty</a>
    <a href="delfaculty.php" class="nav-item nav-link" tabindex="-1">Delete Faculty</a>
</nav>

<p><h2>University Faculty Directory Search:</h2></p>
<form action="faculty-search.php" method=get>
	Enter Faculty name: <input type=text size=20 name="name">
	<p>Enter Faculty ID number: <input type=text size=5 name="id">
        <p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter a faculty name or ID number and submit the form.";
}
else {
// Create connection

 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
 if (!empty($_GET["name"]))
 {
   $profName = $_GET["name"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT id, name, dept_name FROM instructor where name=?"); //prepare the statement
   $sqlstatement->bind_param("s",$profName); //insert the String variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 elseif (!empty($_GET["id"]))
 {
   $profID = $_GET["id"]; //gets name from the form
   $sqlstatement = $conn->prepare("SELECT id, name, dept_name FROM instructor where id=?"); //prepare the statement
   $sqlstatement->bind_param("i",$profID); //insert the integer variable into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   $result = $sqlstatement->get_result(); //return the results
   $sqlstatement->close();
 }
 else {
	 echo "<b>Please enter a name or an ID number</b>";
 }
   if ($result->num_rows > 0) {
     	// Setup the table and headers
	echo "<center><table><tr><th>ID</th><th>Name</th><th>Department</th></tr>";
	// output data of each row into a table row
	 while($row = $result->fetch_assoc()) {
		 echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td> ".$row["dept_name"]."</td></tr>";
   	}
	
	echo "</table> </center>"; // close the table
	echo "There are ". $result->num_rows . " results.";
	// Don't render the table if no results found
   	} else {
               echo "$name not found. 0 results";
	} 
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
<p> Thanks for using the directory search! 
</body>
</html>
