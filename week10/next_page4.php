<?php 
	// start the session, as per 
    session_start(); 
	//you can get rid of all session variables using the 
	//session_destroy() function 
    //but first it is recommended you call the session_unset() function
	//it is a bug in certain versions of PHP
	session_unset();	//unsets the whole $_SESSION array
	session_destroy();	//destroys it by freeing up the memory
?> 
Welcome to my website 
	<strong><?= $_SESSION['name'];?></strong>!
	<br />  <!-- Nothing should be displayed -->