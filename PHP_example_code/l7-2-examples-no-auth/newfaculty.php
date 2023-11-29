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
//run a query to get all department names  
$sqlstatement = $conn->prepare("SELECT distinct dept_name FROM department order by dept_name asc"); //prepare the statement
$sqlstatement->execute(); //execute the query
$departments = $sqlstatement->get_result(); //return the results we'll use them in the web form
$sqlstatement->close();
?>


</style
<body>
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
   $conn->close();
 } //end else condition where form is submitted
  ?> <!-- this is the end of our php code -->
</body>
</html>
