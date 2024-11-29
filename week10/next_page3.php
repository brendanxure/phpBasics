<?php
	// start the session, always needs to be done at the top of a page that is going to use sessions
	session_start();
	echo "<p>Session Id: <em>".session_id()." (session id is still available before the destroy) <br/><br/>";
	/*
		you can remove all elements off the $_SESSION array, and remove the session id by using the session_destroy() function.
	*/
	if(isset($_SESSION))
		 session_destroy(); 
?> 
	<strong>What is on the session after <em>destroying the $_SESSION[]</em></strong><br/>
	<p>Session Id: <em><?php echo session_id(); ?></em> (even the session id is dumped)</p>
	<pre><?php print_r($_SESSION);?></pre>
	<br />Let's see what happens on the <a href="next_page4.php">next page.</a><br /><br /> 