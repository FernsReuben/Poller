<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

include '/home/nbufordh/phpconfig/config.inc';
// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
//run a query to get all department names  
$sqlstatement = $conn->prepare("SELECT distinct dept_name FROM department order by dept_name asc"); //prepare the statement
$sqlstatement->execute(); //execute the query
$departments = $sqlstatement->get_result(); //return the results we'll use them in the web form
$sqlstatement->close();

function displayFaculty() {
  global $conn; //reference the global connection object (scope)
  $sql = "SELECT * FROM instructor";
        $result = $conn->query($sql);

     if ($result->num_rows > 0) {
        // Setup the table and headers
        echo "<Center><table><tr><th>ID</th><th>Name</th><th>Department</th><th>Salary</th></tr>";
       // output data of each row into a table row
       while($row = $result->fetch_assoc()) {
           echo "<tr><td>".$row["ID"]."</td><td>".$row["name"]."</td><td> ".$row["dept_name"]."</td><td>$".$row["salary"]."</td></tr>";
           }
      echo "</table></center>"; // close the table
      echo "There are ". $result->num_rows . " results.";
     // Don't render the table if no results found
    } else {
      echo "0 results";
                                                                                                                      }
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Faculty</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
		table, th, td { border: 1px solid black; }
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
<p><h2>New Faculty Member Entry Form:</h2></p>
<form action="newfaculty.php" method=get>
	Enter Faculty name: <input type=text size=20 name="name">
	<p>Enter Faculty ID number: <input type=text size=5 name="id">
	<p>Select Faculty Department: 
	<select name="department"
        <?php //iterate through the results of the department query to build the web form
	while($department = $departments->fetch_assoc()) {
	?>
		<option value="<?php echo $department["dept_name"]; ?>"><?php echo $department["dept_name"]; ?>
		</option>
	<?php } //end while loop ?>
	</select> 

	<p>Enter Faculty Salary: <input type=text size=10 name="salary">
	<p> <input type=submit value="submit">
                <input type="hidden" name="form_submitted" value="1" >
</form>


<?php //starting php code again!
if (!isset($_GET["form_submitted"]))
{
		echo "Hello. Please enter new faculty information and submit the form.";
    echo "<p>Here is a list of the current faculty members:";
    displayFaculty();
}
else {
  if (!empty($_GET["name"]) && !empty($_GET["id"]) && !empty($_GET["salary"]))
{
   $profName = $_GET["name"]; //gets name from the form
   $profID = $_GET["id"]; //gets id from the form
   $profDept = $_GET["department"]; //get department from the form
   $profSalary = $_GET["salary"]; //get salary from the form
   $sqlstatement = $conn->prepare("INSERT INTO instructor values(?, ?, ?, ?)"); //prepare the statement
   $sqlstatement->bind_param("sssd",$profID,$profName,$profDept,$profSalary); //insert the variables into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   echo $sqlstatement->error; //print an error if the query fails
   $sqlstatement->close();
 }
 else {
	 echo "<b> Error: Please enter a name, an ID number and a salary to proceed.</b>";
 }
 
   echo "<p>Here is a list of the current faculty members:";
   displayFaculty();
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
</body>
</html>
