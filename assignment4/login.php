<?php
/** 
* This file contains the body section of the login page 
* 
* 
* PHP version 7.1 
* 
* @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
* @version 7.1 (November 26, 2024)
*/ 
    $title = "Login Page";
    $file = "login.php"; 
    $description = "Login page for my Students Grade Portal"; 
    $date = "November 26, 2024"; 
    $banner = "DC Log in";
    $is_error = FALSE;
    $error = "";
    $min_length = 9;
    $password="";
    $alert = 'success';

    include("./includes/header.php");

?>

<?php
    // Check if there is a session message
    $msg = isset($_SESSION["message"])?$_SESSION["message"]:"";

    if(!isset($_COOKIE["LOGIN_COOKIE"])){
        $_COOKIE["LOGIN_COOKIE"] = "";
    }

    // Get and post method
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        //default mode when the page loads the first time
        //can be used to make decisions and initialize variables
        $student_id = $_COOKIE["LOGIN_COOKIE"] || "";
        $password = "";
    }
    else if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        //the page got here from submitting the form, let's try to process

        // Get the user id from the form and remove white-space
        $student_id = trim($_POST["student_id"]); 
        // the value of the password input box on the form was retrieved and  white-space removed
        $password = trim($_POST["password"]);

        //let's do some data validation
        if(!isset($student_id) || $student_id == "" || !isset($password) || $password == "")
        {
            $alert = 'info';
            //means the user did not enter anything
            $error = "Enter student ID and password.";
        }
        else if (!is_numeric($student_id) || strlen($student_id) != $min_length){
            $alert = 'info';
            //means the user entered the invalid student_id 
            $error = "Enter correct student ID and password.";
        } 
        else 
        {
            $conn = db_connect();
            
            // prepare sql statement for execution
            prepare_statement($conn, "login");
            
            // Execute the sql statement
            $result = pg_execute($conn,"login",array($student_id));

            //should check if a record was retrieved
            $user = pg_fetch_assoc($result, 0); 

            //set up time dependent stuff
            $today = date("Ymd");
            $now = date("Y-m-d G:i:s");
            
            //here is the file stuff
            $handle = fopen("./logs/activity.log", 'a');

            if(!$user)
            {
                $alert = 'info';
                $error = "Student ID and Password is incorrect.";
                fwrite($handle, $now." - ".$student_id." - ". "UnSuccessful log in" . "\n");

                fclose($handle);
            }
            else if(password_verify($password, $user['password']))
            {
                // Cookies
                $cookie_name = "LOGIN_COOKIE";
                $cookie_value = $student_id;
                setcookie($cookie_name, $cookie_value, time() + 60*60*24*30, "/");
                //user authenticated
	            $_SESSION['user'] = $user; 
                $error = "Sucessfully logged in";

                // prepare sql statement for execution
                prepare_statement($conn, "last_access");

                // Execute the sql statement to update last access
                $result_last_access = pg_execute($conn,"last_access",array($student_id));

                if($result_last_access)
                {
                    fwrite($handle, $now." - ".$student_id." - ". "Successfully logs in" . "\n");

                    fclose($handle);

                    header("Location:grades.php");
                    ob_flush();    
                }
                else
                {

                    fwrite($handle, $now." - ".$student_id." - ". "Last access not updated" . "\n");

                    fclose($handle);
                }


            }
            else 
            {
                $alert = 'info';
                $error = "Enter correct student ID and password.";

                fwrite($handle, $now." - ".$student_id." - ". "UnSuccessful log in" . "\n");

                fclose($handle);
            }
        }

    }
?>
<main class="form-signin w-100 m-auto min-vh-70">
    <?php if($error != "" ||  $msg != '')
    {
        echo "<div class='alert alert-$alert alert-dismissible fade show' role='alert'>";
        echo $error == '' ? $msg : $error;
        echo " <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
              </div>";
        $_SESSION["message"] = "";
    }
    ?>
  <form action="<?php echo $_SERVER['PHP_SELF'];  ?>" method="post" novalidate>
    <img class="mb-4" src="./includes/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
    <h1 class="h3 mb-3 fw-normal">Please Log in</h1>

    <div class="form-floating">
      <input type="text" class="form-control" name="student_id" value="<?php echo $_COOKIE["LOGIN_COOKIE"];?>" id="floatingInput" placeholder="100900***">
      <label for="floatingInput">Student ID</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>
    <button class="btn btn-primary mt-4 w-100 py-2" type="submit">Log in</button>
    <!-- <p class="mt-5 mb-3 text-body-secondary">&copy; 2017–2024</p> -->
  </form>
</main>

<?php
    include("./includes/footer.php");
?>