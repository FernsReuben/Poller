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
$new_street_number = $new_street_name = $new_city = $new_state = $new_zip = $street_number_err = $street_name_err = $city_err = $state_err = $zip_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 


        // Validate Street number
        if(empty(trim($_POST["new_street_number"]))){
        $street_number_err = "Please enter your street number.";
    } elseif(!preg_match('/^[0-9]+$/', trim($_POST["new_street_number"]))){
        $street_number_err = "Street number can only contain numbers";
    } else{
        $new_street_number = trim($_POST["new_street_number"]);
    }

        // Validate Street Name
        if(empty(trim($_POST["new_street_name"]))){
        $street_name_err = "Please enter your street name.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["new_street_name"]))){
        $street_name_err = "Street name can only contain letters";
    } else{
        $new_street_name = trim($_POST["new_street_name"]);
    }

        // Validate City
        if(empty(trim($_POST["new_city"]))){
        $city_err = "Please enter your city.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["new_city"]))){
        $city_err = "City name can only contain letters";
    } else{
        $new_city = trim($_POST["new_city"]);
    }

        // Validate State
        if(empty(trim($_POST["new_state"]))){
        $state_err = "Please enter your state.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["new_state"]))){
        $state_err = "Name can only contain letters";
    } else{
        $new_state = trim($_POST["new_state"]);
    }

        // Validate Zip
        if(empty(trim($_POST["new_zip"]))){
        $zip_err = "Please enter your zip code.";
    } elseif(!preg_match('/^[0-9]+$/', trim($_POST["new_zip"]))){
        $zip_err = "Zip code can only contain numbers";
    } else{
        $new_zip = trim($_POST["new_zip"]);
    }



    // Check input errors before updating the database
    if(empty($street_number_err) && empty($street_name_err) && empty($city_err) && empty($state_err) && empty($zip_err)){


        $current_username = $_SESSION["username"];
        // Prepare an update statement


        
        
        $stmt = $mysqli->prepare("UPDATE User SET street_number = ?, street_name = ?, city = ?, state = ?, zip = ? WHERE username = ?");
        
        $stmt->bind_param("isssis", $new_street_number, $new_street_name, $new_city, $new_state, $new_zip, $current_username);
            

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                
                header("location: account-info.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    
    
    // Close connection
    $mysqli->close();

    }

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change address</title>
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
                <input type="text" name="new_street_number" class="form-control <?php echo (!empty($street_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_street_number; ?>">
                <span class="invalid-feedback"><?php echo $street_number_err; ?></span>
            </div>

            <div class="form-group">
                <label>Street Name</label>
                <input type="text" name="new_street_name" class="form-control <?php echo (!empty($street_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_street_name; ?>">
                <span class="invalid-feedback"><?php echo $street_name_err; ?></span>
            </div>

            <div class="form-group">
                <label>City</label>
                <input type="text" name="new_city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_city; ?>">
                <span class="invalid-feedback"><?php echo $city_err; ?></span>
            </div>

            <div class="form-group">
                <label>State</label>
                <input type="text" name="new_state" class="form-control <?php echo (!empty($state_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_state; ?>">
                <span class="invalid-feedback"><?php echo $state_err; ?></span>
            </div>

            <div class="form-group">
                <label>Zip</label>
                <input type="text" name="new_zip" class="form-control <?php echo (!empty($zip_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $new_zip; ?>">
                <span class="invalid-feedback"><?php echo $zip_err; ?></span>
            </div>

            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="account-info.php">Cancel</a>
            </div>
        </form>
    </div>    
</body>
</html>