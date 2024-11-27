<?php
	// start the session, always needs to be done at the top of a page that is going to use sessions
	session_start();
	/*
		you can remove a specific element off the $_SESSION array by using the unset() function.
		NOTE: unset() works for ANY and ALL arrays in PHP
	*/
	if(isset($_SESSION['name']))
		 unset($_SESSION['name']); 
?> 
	<strong>What is on the session after "unset"'ting <em>$_SESSION['name']</em></strong><br/>
	<p>Session Id: <em><?php echo session_id(); ?></em> (same as before)</p>
	<pre><?php print_r($_SESSION);?></pre>
	<br />
	<br />Let's see what happens on the <a href="next_page2.php">next page.</a>
