<html>
<style>
table, th, td {
  border: 1px solid black;
}
<?php
include '/home/wreed6/phpconfig/config.inc';
// Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
   die("Connection failed: " . $conn->connect_error);
 }
 function displayFaculty() {
	 global $conn; //reference the global connection object (scope)
	 $sql = "SELECT id, name, dept_name FROM instructor";
         $result = $conn->query($sql);

	    if ($result->num_rows > 0) {
	       // Setup the table and headers
	       echo "<table><tr><th>ID</th><th>Name</th><th>Department</th><th>Click To Remove</th></tr>";
	      // output data of each row into a table row
	      while($row = $result->fetch_assoc()) {
	          echo "<tr><td>".$row["id"]."</td><td>".$row["name"]."</td><td> ".$row["dept_name"]."</td><td><a href=\"delfaculty.php?form_submitted=1&id=".$row["id"]."\">Remove</a></td></tr>";
	          }
	     echo "</table>"; // close the table
	     echo "There are ". $result->num_rows . " results.";
	    // Don't render the table if no results found
	   } else {
	     echo "0 results";
		                                                                                                                   }
  }


?>


</style
<body>
<p><h2>List of Current Faculty:</h2></p>
<?php
displayFaculty();
?>
<form action="delfaculty.php" method=get>
                <input type="hidden" name="form_submitted" >
                <input type="hidden" name="id" >
</form>


<?php //starting php code again!
if (isset($_GET["form_submitted"])){
  if (!empty($_GET["id"]) && !empty($_GET["form_submitted"]))
{
   $profID = $_GET["id"]; //gets id from the form
   $sqlstatement = $conn->prepare("DELETE FROM instructor where id =?"); //prepare the statement
   $sqlstatement->bind_param("s",$profID); //insert the variables into the ? in the above statement
   $sqlstatement->execute(); //execute the query
   echo $sqlstatement->error; //print an error if the query fails
   $sqlstatement->close();
 }
 else {
	 echo "<b> Error: Something went wrong with the form.</b>";
 }
header("Refresh:0;url=delfaculty.php"); //refresh the page to show the faculty is gone
}
   $conn->close();
  ?> <!-- this is the end of our php code -->
</body>
</html>
