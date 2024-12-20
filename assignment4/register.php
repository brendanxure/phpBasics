<?php
    /** 
    * This file contains the body section of the register page 
    * 
    * 
    * PHP version 7.1 
    * 
    * @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
    * @version 7.1 (November 26, 2024)
    */ 
    $title = "Register Page";
    $file = "register.php"; 
    $description = "Register page for my Students Grade Portal"; 
    $date = "November 26, 2024"; 
    $banner = "DC Create Account";
    include("./includes/header.php");   

    $error = "";

    if(isset($_SESSION['user'])){
        // A message stating the user is already logged in
        $_SESSION["message"] = "You are already logged in";
        header("Location:grades.php");
        ob_flush();
    }

    if($_SERVER["REQUEST_METHOD"] == "GET"){
        //default mode when the page loads the first time
        //can be used to make decisions and initialize variables
        $first_name = "";
        $last_name = "";
        $email = "";
        $date_input = ""; 
        $password = ""; 
        $confirm_password = ""; 
        $program = "";
        $clear_date = NULL;
        $input_user_id = "";
        $error_first_name = "";
        $error_last_name = "";
        $error_email = "";
        $error_user_id = "";
        $error_date_input = "";
        $error_password = "";
        $error_program = "";
        $error_confirm_password = "";
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Collect all the values of the form input and assign them to a variable
        $first_name = trim($_POST["inputFirstName"]);
        $last_name = trim($_POST["inputLastName"]);
        $email = trim($_POST["inputEmail"]);
        $input_user_id = trim($_POST["inputUserID"]);
        $date_input = $_POST["date"]; 
        $password = trim($_POST["inputPassword"]); 
        $confirm_password = trim($_POST["inputConfirmPassword"]); 
        $program = isset($_POST["program"]) ? $_POST["program"] : '';
        $max_length_password = 15;
        $min_length_password = 7;
        $max_name_length = 20;
        $min_user_id = 100900000;
        $clear_date = FALSE;
        $min_length = 9;
        // Get today's date in the same format as the input (YYYY-MM-DD)
        $input_date = date($date_input);
        $today = date("Y-m-d");

        if(!isset($input_user_id) || $input_user_id == "")
        {
            $error_user_id = "User ID cannot be empty";
            $input_user_id = "";
        }
        else if(!is_numeric($input_user_id))
        {
            $error_user_id  = "User ID must be a number";
            $input_user_id = "";
        }
        else if ((int)$input_user_id < $min_user_id)
        {
            $error_user_id  = "User ID is not valid must start from " . $min_user_id;
            $input_user_id = "";
        }
        else if(strlen($input_user_id) !=  $min_length)
        {
            $error_user_id = "User ID should be 9 digits";
            $input_user_id = "";
        }

        // Checks if the first name input is empty
        if(!isset($first_name) || $first_name == ""){
            $error_first_name = "First Name cannot be empty";
            $first_name = "";
        }
        else if (strlen($first_name) > $max_name_length)
        {
            $first_name = "";
            $error_first_name = "First name cannot be greater than $max_name_length characters";
        }
        
        // Checks if the last name input is empty
        if(!isset($last_name) || $last_name == "")
        {
            $error_last_name = "Last Name cannot be empty";
            $last_name = "";
        }
        else if (strlen($last_name) > $max_name_length)
        {
            $last_name = "";
            $error_last_name = "Last name cannot be greater than $max_name_length characters";
        }

        // Checks if the email input is empty
        if(!isset($email) || $email == "")
        {
            $error_email = "Email cannot be empty";
            $email = "";
        }
        // check if the email address is a valid email address
        else if (!filter_var($email,FILTER_VALIDATE_EMAIL))
        {
            $error_email = "$email is an invalid email address";
            $email = "";
        }

        // Checks if the program input is empty
        if(!isset($program) || $program == "")
        {
            $error_program = "Please select a program";
            $program = "";
        }

        // Checks if the date input is empty
        if(!isset($date_input) || $date_input == "")
        {
            $error_date_input = "Please enter your date of birth";
        }
        else if($input_date > $today)
        {
            $clear_date = TRUE;
            $error_date_input = "Date of birth cannot be greater than today\n";
        }

        if(!isset($password) || $password == "")
        {
            $error_password = "Please enter a password";
            $password = "";
        }
        else if(strlen($password) > $max_length_password || strlen($password) < $min_length_password)
        {
            $error_password = "Password should be between $min_length_password and  $max_length_password characters";
            $password = "";
            $confirm_password = "";
        }
        else if ($password != $confirm_password)
        {
            $error_password = "The password and confirm password does not match";
            $error_confirm_password = "The password and confirm password does not match";
            $password = "";
            $confirm_password = "";
        } 
        
        if(!empty($error_user_id) || !empty($error_first_name) || !empty($error_last_name) || !empty($error_email) || !empty($error_date_input) || !empty($error_program) || !empty($error_password) || !empty($error_confirm_password))
        {
            echo "<div class='alert alert-info'>Error: Check the form and input appropriate information</div>";
        }
        else 
        {
            $conn = db_connect();
    
            // Prepare statement for execution for registering user
            prepare_statement($conn, "register_user");
            // variable for user ID
            $user_id = (int)$input_user_id;
            // Execute the sql statement for registering user
            $result_user = pg_execute($conn,"register_user",array($user_id, $first_name, $last_name, $email, $input_date, $password));
    
            //set up time dependent stuff
            $today = date("Ymd");
            $now = date("Y-m-d G:i:s");
            
            //here is the file stuff
            $handle = fopen("./logs/activity.log", 'a');
            if($result_user){

                // Prepare statement for execution for registering student to program
                prepare_statement($conn, "register_student");
                // Execute the sql statement for registering student to program
                $result_student = pg_execute($conn,"register_student",array($user_id, $program));
        
                if($result_student)
                {
                    // Add message to display in the login page to the session message
                    $_SESSION["message"] = "You have successfully created an account with user id: $user_id";

                    fwrite($handle, $now." - ".$user_id." - ".$first_name." ".$last_name." - "."Successfully Registered" . "\n");

                    fclose($handle);

                    // Redirect the user to the login page
                    header("Location:login.php");
                    ob_flush();
                }
                else
                {
                    echo "<div class='alert alert-info'>Error in registration: " . htmlspecialchars(pg_last_error($conn)) . "</div>";

                    fwrite($handle, $now." - ".$user_id." - ". "Unsuccessful Registration" . "\n");

                    fclose($handle);
                }
            }
            else
            {
                echo "<div class='alert alert-info'>Error in registration: " . htmlspecialchars(pg_last_error($conn)) . "</div>";

                fwrite($handle, $now." - ".$user_id." - ". "Unsuccessful Registration" . "\n");

                fclose($handle);
            }
        }
    }
?>

<main>
  <div class="container py-4 min-vh-70">
        <form class="row g-3" action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="post" novalidate>
            <div class="col-md-6">
                <label for="inputUserID" class="form-label">User ID</label>
                <input type="text" required name="inputUserID" value="<?php echo "$input_user_id"; ?>" class="form-control <?php echo empty($error_user_id) ? "" : 'is-invalid' ?>" id="inputUserID" aria-label="inputUserID">
                <div class="invalid-feedback">
                    <?php echo  $error_user_id ; ?>
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputFirstName" class="form-label">First Name</label>
                <input type="text" required name="inputFirstName" value="<?php echo "$first_name"; ?>" class="form-control <?php echo empty($error_first_name) ? "" : 'is-invalid' ?>" id="inputFirstName" aria-label="First name">
                <div class="invalid-feedback">
                    <?php echo $error_first_name ; ?>
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputLastName" class="form-label">Last Name</label>
                <input type="text" required class="form-control <?php echo empty($error_last_name) ? "" : 'is-invalid' ?>" name="inputLastName" value="<?php echo "$last_name"; ?>" id="inputLastName" aria-label="Last name">
                <div class="invalid-feedback">
                    <?php echo $error_last_name ; ?>
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail" class="form-label">Email</label>
                <input type="email" required name="inputEmail" value="<?php echo "$email"; ?>" class="form-control <?php echo empty($error_email) ? "" : 'is-invalid' ?>" id="inputEmail">
                <div class="invalid-feedback">
                    <?php echo $error_email ; ?>
                </div>
            </div>
            <div class="form-check col-md-2 pt-4 mx-2">
                <input class="form-check-input <?php echo empty($error_program) ? "" : 'is-invalid' ?>" type="radio" name="program" id="cpga" value="CPGA" <?php echo $program == "CPGA" ? "checked" : ""; ?>  required>
                <label class="form-check-label" for="cpga">
                    CPGA
                </label>
                <div class="invalid-feedback">
                    <?php echo $error_program ; ?>
                </div>
            </div>
            <div class="form-check col-md-2 py-4 mx-2">
                <input class="form-check-input <?php echo empty($error_program) ? "" : 'is-invalid' ?>" type="radio" name="program" id="cpgm" value="CPGM" <?php echo $program == "CPGM" ? "checked" : ""; ?> required>
                <label class="form-check-label" for="cpgm">
                    CPGM
                </label>
                <div class="invalid-feedback">
                    <?php echo $error_program ; ?>
                </div>
            </div>
            <div class="col-md-6">
                <label for="dateInputIcon" class="form-label">Date of Birth</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                    <input type="date" value="<?php echo $clear_date ? '' : (isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''); ?>"
                    required class="form-control <?php echo empty($error_date_input) ? "" : 'is-invalid' ?>" id="dateInputIcon" name="date">
                    <div class="invalid-feedback">
                        <?php echo $error_date_input ; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputPassword" class="form-label">Password</label>
                <input type="password" required name="inputPassword" class="form-control <?php echo empty($error_password) ? "" : 'is-invalid' ?>" id="inputPassword">
                <div class="invalid-feedback">
                    <?php echo $error_password ; ?>
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputConfirmPassword" class="form-label">Confrim Password</label>
                <input type="password" required name="inputConfirmPassword" class="form-control <?php echo empty($error_confirm_password) ? "" : 'is-invalid' ?>" id="inputConfirmPassword">
                <div class="invalid-feedback">
                    <?php echo $error_confirm_password ; ?>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</main>
<?php
    include("./includes/footer.php");
?>