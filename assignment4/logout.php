<?php
/** 
* This file contains the section of the log out 
* 
* 
* PHP version 7.1 
* 
* @author  Brendan Obilo <brendan.obilo@dcmail.ca>       
* @version 7.1 (November 26, 2024)
*/ 
    $title = "Logout Page";
    $file = "logout.php"; 
    $description = "Log out page for my Students Grade Portal"; 
    $date = "November 26, 2024"; 
    $banner = "DC Log out";
   include("./includes/header.php");

    // user detail
    $user = $_SESSION['user'];
    $user_id = $user['user_id'];

    //set up time dependent stuff
    $today = date("Ymd");
    $now = date("Y-m-d G:i:s");
    
    //here is the file stuff
    $handle = fopen("./logs/activity.log", 'a');

    fwrite($handle, $now." - ".$user_id." - ". "Logs out successfully" . "\n");

    fclose($handle);

    // unset the session 
    session_unset();
    // destroy the session
    session_destroy();
    // restart the session
    session_start();

    // A message stating the user has successfully logged out onto the session:
    $_SESSION["message"] = "You have successfully logged out";

    // Redirect the user to the login.php page:
    header("Location:login.php");
	ob_flush();
    include("./includes/footer.php");
?>