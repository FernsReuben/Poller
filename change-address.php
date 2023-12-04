<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$new_street_number = $new_street_name = $new_city = $new_state = $new_zip = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate new zip
    if(empty(trim($_POST["new_zip"]))){
        $new_zip = "Please enter valid zip code.";     
    } elseif(strlen(trim($_POST["new_zip"])) < 5){
        $new_zip = "Zip code must have atleast 5 numbers.";
    } else{
        $new_zip = trim($_POST["new_zip"]);
    }
    


    // Check input errors before updating the database
    if(empty($street_number_err) && empty($street_name_err) && empty($city_err) && empty($state_err) && empty($zip_err)){
        // Prepare an update statement
        $sql = "UPDATE User SET street_number = ? WHERE id = ?";
        $sql = "UPDATE User SET street_name = ? WHERE id = ?";
        $sql = "UPDATE User SET city = ? WHERE id = ?";
        $sql = "UPDATE User SET state = ? WHERE id = ?";
        $sql = "UPDATE User SET zip = ? WHERE id = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("si", $param_street_number, $param_id);
            $stmt->bind_param("sj", $param_street_name, $param_id);
            $stmt->bind_param("sk", $param_city, $param_id);
            $stmt->bind_param("sl", $param_state, $param_id);
            $stmt->bind_param("sm", $param_zip, $param_id);

            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_street_number = streetNumber_hash($new_street_number, STREETNUMBER_DEFAULT);
            $param_street_name = streetName_hash($new_street_name, STREETNAME_DEFAULT);
            $param_city = city_hash($new_city, CITY_DEFAULT);
            $param_state = state_hash($new_state, STATE_DEFAULT);
            $param_zip = zip_hash($new_zip, ZIP_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Close connection
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Change Address</h2>
        <p>Please fill out this form to change your address.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">

            <div class="form-group">
                <label>Street Number</label>
                <input type="street_number" name="new_street_number" class="form-control <?php echo (!empty($new_street_number)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_street_number; ?>">
                <span class="invalid-feedback"><?php echo $new_street_number; ?></span>
            </div>

            <div class="form-group">
                <label>Street Name</label>
                <input type="street_name" name="new_street_name" class="form-control <?php echo (!empty($new_street_name)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_street_name; ?>">
                <span class="invalid-feedback"><?php echo $new_street_name; ?></span>
            </div>

            <div class="form-group">
                <label>City</label>
                <input type="city" name="new_city" class="form-control <?php echo (!empty($new_city)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_city; ?>">
                <span class="invalid-feedback"><?php echo $new_city; ?></span>
            </div>

            <div class="form-group">
                <label>State</label>
                <input type="state" name="new_state" class="form-control <?php echo (!empty($new_state)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_state; ?>">
                <span class="invalid-feedback"><?php echo $new_state; ?></span>
            </div>

            <div class="form-group">
                <label>Zip</label>
                <input type="zip" name="new_zip" class="form-control <?php echo (!empty($new_zip)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_zip; ?>">
                <span class="invalid-feedback"><?php echo $new_szip; ?></span>
            </div>

            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="welcome.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>