<html>
<p><h1 align="center">Poller</h1></p>
	<body>
    <p align="center">For each question, enter the letter of the statement you most agree with</p>
	<form action="surveyList.php" method=get>
		<br><input type=text size=1 name="choice1"><br>
        <br><input type=text size=1 name="choice1"><br>
        <br><input type=text size=1 name="choice1"><br>
        <br><input type=text size=1 name="choice1"><br>
		<input align="center" type="submit" value="submit" onclick= "window.location.href='https://dbdev.cs.kent.edu/~tbaker60/Poller/surveyList.php';">
		<input type="hidden" name="form_submitted" value="1" >
	</form>

<?php 

if (!isset($_GET["form_submitted"]))
{ 
	echo "<p>Please complete all questions and submit the form.";
}

else { 
	echo "Thanks!" . $_GET["choice"];
}

?>
 
	</body>
</html>