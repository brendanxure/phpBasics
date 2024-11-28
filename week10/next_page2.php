<?php
	// start the session, always needs to be done at the top of a page that is going to use sessions
	session_start();
	/*
		you can remove all elements off the $_SESSION array by using the session_unset() function.
		NOTE: the session and it's id will still exist, only it will be empty
	*/
	if(isset($_SESSION))
		 session_unset(); 
?> 
	<strong>What is on the session after "unset"'ting <em>the whole session</em></strong><br/>
	<p>Session Id: <em><?php echo session_id(); ?></em> (same as before, this is the only thing left)</p>
	<pre><?php print_r($_SESSION);?></pre>
	<br />
	<br />Let's see what happens on the <a href="next_page3.php">next page.</a>
