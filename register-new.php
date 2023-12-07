<?php
// Include config file
require_once "config.php";
 
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Define variables and initialize with empty values
$username = $password = $confirm_password = $first_name = $last_name = $street_number = $street_name = $city = $state = $zip = $email = $apt_number = $currency = $User_ID = "";
$username_err = $password_err = $confirm_password_err = $first_name_err = $last_name_err = $street_number_err = $street_name_err = $city_err = $state_err = $zip_err = $email_err =  $apt_number_err = $currency_err = $User_ID_err = "";

 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT User_ID FROM User WHERE username = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong1. Please try again later.";
            }

            // Close statement
            $stmt->close();
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    
     // Validate First Name
     if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter your first name.";
    } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["first_name"]))){
        $first_name_err = "First name can only contain letters";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

         // Validate Last Name
         if(empty(trim($_POST["last_name"]))){
            $last_name_err = "Please enter your last name.";
        } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["last_name"]))){
            $last_name_err = "Last name can only contain letters";
        } else{
            $last_name = trim($_POST["last_name"]);
        }

         // Validate Street number
         if(empty(trim($_POST["street_number"]))){
            $street_number_err = "Please enter your street number.";
        } elseif(!preg_match('/^[0-9]+$/', trim($_POST["street_number"]))){
            $street_number_err = "Street number can only contain numbers";
        } else{
         $street_number = trim($_POST["street_number"]);
        }

          // Validate Street Name
          if(empty(trim($_POST["street_name"]))){
            $street_name_err = "Please enter your street name.";
        } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["street_name"]))){
            $street_name_err = "Street name can only contain letters";
        } else{
            $street_name = trim($_POST["street_name"]);
        }

         // Validate City
         if(empty(trim($_POST["city"]))){
            $city_err = "Please enter your city.";
        } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["city"]))){
            $city_err = "City name can only contain letters";
        } else{
            $city = trim($_POST["city"]);
        }

         // Validate State
         if(empty(trim($_POST["state"]))){
            $state_err = "Please enter your state.";
        } elseif(!preg_match('/^[a-zA-Z]+$/', trim($_POST["state"]))){
            $state_err = "Name can only contain letters";
        } else{
            $state = trim($_POST["state"]);
        }

         // Validate Zip
         if(empty(trim($_POST["zip"]))){
            $zip_err = "Please enter your zip code.";
        } elseif(!preg_match('/^[0-9]+$/', trim($_POST["zip"]))){
            $zip_err = "Zip code can only contain numbers";
        } else{
            $zip = trim($_POST["zip"]);
        }

        // Validate Email
        if(empty(trim($_POST["email"]))){
            $email_err = "Please enter your email address.";
        } elseif(!filter_var(trim($_POST["email"]))) {
            $email_err = "Invalid email format.";
        } else{
            $email = trim($_POST["email"]);
        }




        // Check input errors before inserting in database
      if (empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($street_number_err) && empty($street_name_err) && empty($city_err) && empty($state_err) && empty($zip_err) && empty($email_err)) {


        // Prepare an insert statement



        $stmt = $mysqli->prepare("INSERT INTO User (User_ID, first_name, last_name, street_number, street_name, apt_number, city, state, zip, currency, email, username, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // Bind variables to the prepared statement as parameters
        
        

        $result = $mysqli->query("SELECT MAX(User_ID) AS max_user_id FROM User");
        $row = $result->fetch_assoc();
        $max_user_id = $row['max_user_id'];
        
        // Increment User_ID
        $User_ID = $max_user_id + 1;


        $stmt->bind_param("issisissiisss", $User_ID, $first_name, $last_name, $street_number, $street_name, $apt_number, $city, $state, $zip, $currency, $email, $username, $password);

        

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Redirect to login page
            
            header("location: login.php");
        } else {

            echo("Error description: " . $conn->error);
            echo "Oops! Something went wrong10. Please try again later.";
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Street Number</label>
                <input type="text" name="street_number" class="form-control <?php echo (!empty($street_number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street_number; ?>">
                <span class="invalid-feedback"><?php echo $street_number_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Street Name</label>
                <input type="text" name="street_name" class="form-control <?php echo (!empty($street_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street_name; ?>">
                <span class="invalid-feedback"><?php echo $street_name_err; ?></span>
            </div> 
            <div class="form-group">
                <label>City</label>
                <input type="text" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city; ?>">
                <span class="invalid-feedback"><?php echo $city_err; ?></span>
            </div> 
            <div class="form-group">
                <label>State</label>
                <input type="text" name="state" class="form-control <?php echo (!empty($state_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $state; ?>">
                <span class="invalid-feedback"><?php echo $state_err; ?></span>
            </div> 
            <div class="form-group">
                <label>Zip</label>
                <input type="text" name="zip" class="form-control <?php echo (!empty($zip_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $zip; ?>">
                <span class="invalid-feedback"><?php echo $zip_err; ?></span>
            </div> 
            <div class="form-group">
                <label>email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div> 
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>